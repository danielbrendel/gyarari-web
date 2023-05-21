<?php

/*
    Asatru PHP - Example controller

    Add here all your needed routes implementations related to 'index'.
*/

/**
 * Example index controller
 */
class IndexController extends BaseController {
	const INDEX_LAYOUT = 'layout';

	/**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(self::INDEX_LAYOUT);
	}

	/**
	 * Handles URL: /
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function index($request)
	{
		return parent::view(['content', 'index']);
	}

	/**
	 * Handles URL: /photos/query
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function queryPhotos($request)
	{
		try {
			$paginate = $request->params()->query('paginate', null);
			$random = $request->params()->query('random', false);
			$search = $request->params()->query('search', null);

			if ($random == false) {
				$photos = PhotoModel::queryPhotos($paginate, $search);
			} else {
				$photos = PhotoModel::queryRandom();
			}

			$data = [];

			foreach ($photos as $photo) {
				$data[] = [
					'id' => $photo->get('id'),
					'slug' => $photo->get('slug'),
					'title' => $photo->get('title'),
					'name' => $photo->get('name'),
					'tags' => explode(' ', $photo->get('title')),
					'photo_thumb' => $photo->get('photo_thumb'),
					'photo_full' => $photo->get('photo_full'),
					'created_at' => $photo->get('created_at'),
					'diffForHumans' => (new Carbon($photo->get('created_at')))->diffForHumans(),
					'viewCount' => ViewCountModel::viewForItem($photo->get('id'))
				];
			}

			return json([
				'code' => 200,
				'data' => $data
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /photo/{id}/report
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function reportPhoto($request)
	{
		try {
			$photo = $request->arg('id', null);

			$item = PhotoModel::where('id', '=', $photo)->first();
			if (!$item) {
				throw new \Exception('Photo not found: ' . $photo);
			}

			ReportModel::addReport($photo);

			return json([
				'code' => 200,
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /recent
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function viewRecent($request)
	{
		return redirect('/');
	}

	/**
	 * Handles URL: /random
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function viewRandom($request)
	{
		return parent::view(['content', 'random']);
	}

	/**
	 * Handles URL: /search
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function viewSearch($request)
	{
		$tags = TagsModel::getTagList();

		return parent::view(['content', 'search'], [
			'tags' => $tags
		]);
	}

	/**
	 * Handles URL: /upload
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function viewUpload($request)
	{
		return parent::view(['content', 'upload'], [
			'captchadata' => CaptchaModel::createSum(session_id())
		]);
	}

	/**
	 * Handles URL: /upload
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function upload($request)
	{
		try {
			$title = $request->params()->query('title', null);
			$name = $request->params()->query('name', null);
			$email = $request->params()->query('email', null);
			$tags = $request->params()->query('tags', null);
			$captcha = $request->params()->query('captcha', null);

			$validator = new Asatru\Controller\PostValidator([
				'title' => 'required|min:5',
				'name' => 'required|min:3',
				'email' => 'required|email',
				'captcha' => 'required|numeric'
			]);

			if (!$validator->isValid()) {
				throw new \Exception(print_r($validator->errorMsgs(), true));
			}

			if ($captcha != CaptchaModel::querySum(session_id())) {
				throw new \Exception(__('app.captcha_invalid'));
			}

			$photo = PhotoModel::store($title, $name, $tags);

			$message = view('mail/mail_layout', ['mail', 'mail/' . getLocale() . '/mail_upload'], [
				'name' => $name,
				'link' => url('/photo/' . $photo->get('id')),
				'removal' => url('/photo/' . $photo->get('id') . '/remove/' . $photo->get('removal_token'))
			])->out(true);

			MailerModule::sendMail($email, env('APP_NAME') . ' - ' . $title, $message);

			FlashMessage::setMsg('success', __('app.photo_shared_successfully'));

			return redirect('/photo/' . $photo->get('id'));
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return back();
		}
	}

	/**
	 * Handles URL: /photo/{id}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function showPhoto($request)
	{
		try {
			$photo = PhotoModel::getPhoto($request->arg('id'));
			
			if (!$photo) {
				throw new \Exception(__('app.photo_not_found'));
			}

			$tags = explode(' ', $photo->get('tags'));

			$diffForHumans = (new Carbon($photo->get('created_at')))->diffForHumans();
			$viewCount = ViewCountModel::viewForItem($photo->get('id'));

			return parent::view(['content', 'photo'], [
				'photo' => $photo,
				'tags' => $tags,
				'diffForHumans' => $diffForHumans,
				'viewCount' => $viewCount
			]);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return redirect('/');
		}
	}

	/**
	 * Handles URL: /photo/{id}/remove/{token}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function removePhoto($request)
	{
		try {
			$id = $request->arg('id', null);
			$token = $request->arg('token', null);

			PhotoModel::removePhoto($id, $token);

			FlashMessage::setMsg('success', __('app.photo_removed'));

			return redirect('/');
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return redirect('/');
		}
	}

	/**
	 * Handles URL: /page/{ident}
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler
	 */
	public function viewPage($request)
	{
		try {
			$ident = $request->arg('ident', null);
			
			$pageItem = PageModel::getPage($ident);

			return parent::view(['content', 'page'], [
				'page' => $pageItem
			]);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return redirect('/');
		}
	}
}
