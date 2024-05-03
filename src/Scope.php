<?php

declare(strict_types=1);

namespace PlayCode\VKPlayLiveSDK;

final class Scope
{
    // Предоставляет доступ к данным, позволяющим запускать трансляцию на канале
    public const CHANNEL_CREDENTIALS = 'channel:credentials';

    // Позволяет назначать роли зрителям канала
    public const CHANNEL_ROLES = 'channel:roles';

    // Предоставляет доступ к функциональности баллов канала от лица зрителя
    public const CHANNEL_POINTS = 'channel:points';

    // Позволяет управлять наградами на канале
    public const CHANNEL_POINTS_REWARDS = 'channel:points:rewards';

    // Позволяет управлять купленными пользователями наградами за баллы канала
    public const CHANNEL_POINTS_REWARDS_DEMANDS = 'channel:points:rewards:demands';

    // Позволяет управлять настройками чата на канале
    public const CHAT_SETTINGS = 'chat:settings';

    // Позволяет отправлять сообщения от имени пользователя
    public const CHAT_MESSAGE_SEND = 'chat:message:send';
}