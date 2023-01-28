<?php

namespace App\Foundation\Alert;

use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use Illuminate\Notifications\Notification;

use App\Foundation\Alert\Exceptions\CouldNotSendNotification;

/**
 * 企业微信通道
 * @author wangzh
 * @date 2022-01-26
 * @package App\Foundation\Alert
 */
class WechatWorkChannel
{
    /** @var Client */
    protected $client;

    /**
     * @var string
     */
    protected $notifyUrl = 'https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=%s';

    /**
     * @var \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $config;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;

        $this->config = config('alert') ?: [];
    }

    /**
     * 发送指定的通知。
     *
     * @param \Illuminate\Notifications\AnonymousNotifiable|\Illuminate\Notifications\RoutesNotifications|mixed $notifiable
     * @param \Illuminate\Notifications\Notification $notification
     *
     * @throws CouldNotSendNotification|\GuzzleHttp\Exception\GuzzleException
     *
     * @return \Psr\Http\Message\ResponseInterface|void
     */
    public function send($notifiable, Notification $notification)
    {
        // 获取推送的通道 如果没有找到就直接返回
        if (!$channel = $this->channelForWechatWork($notifiable)) {
            return;
        }

        // 发送数据
        $data = [
            "msgtype" => "markdown",
            "markdown" => [
                "content" => (string)$notification->toWechatWork($notifiable), // 转化为字符串形式
            ],
        ];

        // 发送请求
        $response = $this->client->post($this->routeNotificationForWechatWork($channel), [
            RequestOptions::JSON => $data
        ]);

        // 判断是否发送成功
        if ($response->getStatusCode() >= 300 || $response->getStatusCode() < 200) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($response);
        }

        return $response;
    }

    /**
     * 获取推送地址
     * @author wangzh
     * @date 2022-01-26
     * @param string $channel
     *
     * @return string
     */
    public function routeNotificationForWechatWork($channel): string
    {
        $config = $this->config[$channel] ?? [];

        return sprintf($config['url'] ?? $this->notifyUrl, $config['key'] ?? '');
    }

    /**
     * 获取通知渠道
     * @author wangzh
     * @date 2022-01-26
     * @param \Illuminate\Notifications\AnonymousNotifiable|\Illuminate\Notifications\RoutesNotifications|mixed $notifiable
     *
     * @return mixed
     */
    public function channelForWechatWork($notifiable)
    {
        // 只有当匿名当才能发送
        if ($notifiable instanceof \Illuminate\Notifications\AnonymousNotifiable) {

            // 获取哪个通道
            $channel = $notifiable->routeNotificationFor('alert');

            // 如果配置文件中没有定义就返回 null
            return array_key_exists($channel, $this->config) ? $channel : null;
        }

        return null;
    }
}
