<?php

/**
 * Class NewsletterModel
 */ 
class NewsletterModel extends \Asatru\Database\Model {
    /**
     * @param $email
     * @return void
     * @throws \Exception
     */
    public static function subscribe($email)
    {
        try {
            $token = md5(random_bytes(55) . $email);

            NewsletterModel::raw('INSERT INTO `' . self::tableName() . '` (email, calendar_week, unsubscribe_token) VALUES(?, ?, ?)', [
                $email,
                0,
                $token
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $token
     * @return void
     * @throws \Exception
     */
    public static function unsubscribe($token)
    {
        try {
            NewsletterModel::raw('DELETE FROM `' . self::tableName() . '` WHERE unsubscribe_token = ?', [
                $token
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $photos
     * @param $limit
     * @return array
     * @throws \Exception
     */
    public static function process($photos, $limit = 10)
    {
        try {
            $result = [];

            $calendar_week = intval((new DateTime(date('Y-m-d')))->format('W'));

            $emails = NewsletterModel::raw('SELECT * FROM `' . self::tableName() . '` WHERE calendar_week <> ? LIMIT ' . $limit, [$calendar_week]);
            foreach ($emails as $email) {
                $message = view('mail/mail_layout', ['mail', 'mail/' . getLocale() . '/mail_newsletter'], ['photos' => $photos, 'email' => $email->get('email'), 'token' => $email->get('unsubscribe_token')])->out(true);

                MailerModule::sendMail($email->get('email'), __('app.newsletter_mail_subject'), $message);

                NewsletterModel::raw('UPDATE `' . self::tableName() . '` SET calendar_week = ? WHERE id = ?', [
                    $calendar_week,
                    $email->get('id')
                ]);

                $result[] = [
                    'email' => $email->get('email'),
                    'calendar_week' => $email->get('calendar_week')
                ];
            }

            return [
                'current_week' => $calendar_week,
                'recipients' => $result
            ];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Return the associated table name of the migration
     * 
     * @return string
     */
    public static function tableName()
    {
        return 'newsletter';
    }
}