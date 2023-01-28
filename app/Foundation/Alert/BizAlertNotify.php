<?php

namespace App\Foundation\Alert;

use Illuminate\Bus\Queueable;
use Illuminate\Container\Container;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Markdown;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * 业务警告通知
 * @author wangzh
 * @date 2022-01-26
 * @package App\Notifications
 */
class BizAlertNotify extends Notification
{
    use Queueable;

    /**
     * 类型
     * @var string
     */
    private $type;

    /**
     * 消息
     * @var string
     */
    private $message;

    /**
     * 扩展参数
     * @var array
     */
    private $params;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($type, $message, $params = [])
    {
        $this->type = $type;
        $this->message = $message;
        $this->params = $params;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [WechatWorkChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => $this->type,
            'message' => $this->message,
            'params' => $this->params,
        ];
    }

    /**
     * 企业微信
     * @author wangzh
     * @date 2022-01-26
     * @param mixed $notifiable
     *
     * @throws
     *
     * @return \Illuminate\Support\HtmlString|string
     */
    public function toWechatWork($notifiable)
    {
        return $this->markdown($this->type, $this->message,  $this->params);

        // 构造 markdown 形式的 数据
//        $markdown = Container::getInstance()->make(Markdown::class);
//        return $markdown->theme($markdown->getTheme())->renderText('mail.alert.wechat_work', $this->toArray($notifiable));
    }

    /**
     * mark down 语法
     * @author wangzh
     * @date 2022-01-26
     * @param string $type
     * @param string $message
     * @param array $data
     * @throws
     * @return string
     */
    public function markdown(string $type, string $message, array $data = [])
    {
        $base =  <<<MARKDOWN
> 异常类型:<font color="comment">{$type}</font>
> 异常描述:<font color="comment">{$message}</font>
MARKDOWN;

        $paramsString = "";

        if ($data) {
            $params = json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);

            $paramsString = <<<MARKDOWN

> 业务参数: <font color="comment"> {$params} </font>
MARKDOWN;
        }

        $createTime = date('Y-m-d H:i:s', time());
        $createTimeString = <<<MARKDOWN

> 发生时间:<font color="comment"> {$createTime} </font>
MARKDOWN;

        return sprintf("%s%s%s", $base, $paramsString,$createTimeString);
    }
}
