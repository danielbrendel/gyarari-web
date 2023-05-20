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
					'diffForHumans' => (new Carbon($photo->get('created_at')))->diffForHumans()
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
			$tags = $request->params()->query('tags', null);
			$captcha = $request->params()->query('captcha', null);

			$validator = new Asatru\Controller\PostValidator([
				'title' => 'required|min:5',
				'name' => 'required|min:3',
				'captcha' => 'required|numeric'
			]);

			if (!$validator->isValid()) {
				throw new \Exception(print_r($validator->errorMsgs(), true));
			}

			if ($captcha != CaptchaModel::querySum(session_id())) {
				throw new \Exception(__('app.captcha_invalid'));
			}

			$photo = PhotoModel::store($title, $name, $tags);

			FlashMessage::setMsg('success', __('app.photo_shared_successfully'));

			return redirect('/photo/' . $photo);
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

			return parent::view(['content', 'photo'], [
				'photo' => $photo,
				'tags' => $tags,
				'diffForHumans' => $diffForHumans
			]);
		} catch (\Exception $e) {
			FlashMessage::setMsg('error', $e->getMessage());
			return redirect('/');
		}
	}
}
