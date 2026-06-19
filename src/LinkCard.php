<?php

/**
 * 生成一个链接卡片 HTML 片段，用于在页面上展示链接标题、描述和图标。
 * 本函数仅返回转义后的 HTML，不发起任何网络请求。
 */

/**
 * 默认卡片配置
 */
function getDefaultCardConfig(): array
{
    return [
        'default_site'       => 'https://i-gameapp.com.cn',
        'default_title'      => '爱游戏',
        'default_description'=> '游戏资讯与社区平台',
        'default_icon'       => '',
        'max_title_length'   => 60,
        'max_desc_length'    => 120,
    ];
}

/**
 * 安全截取字符串，避免 HTML 破坏
 */
function safeTruncate(string $text, int $maxLength): string
{
    if (mb_strlen($text) > $maxLength) {
        $text = mb_substr($text, 0, $maxLength) . '…';
    }
    return htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

/**
 * 生成链接卡片
 *
 * @param string $url         链接地址
 * @param string $title       卡片标题
 * @param string $description 卡片描述
 * @param string $icon        图标 URL（可选）
 * @param array  $config      配置覆盖（可选）
 * @return string             转义后的 HTML 片段
 */
function renderLinkCard(
    string $url = '',
    string $title = '',
    string $description = '',
    string $icon = '',
    array $config = []
): string {
    // 合并配置
    $defaultConfig = getDefaultCardConfig();
    $config = array_merge($defaultConfig, $config);

    // 使用默认值填充空参数
    if (empty($url)) {
        $url = $config['default_site'];
    }
    if (empty($title)) {
        $title = $config['default_title'];
    }
    if (empty($description)) {
        $description = $config['default_description'];
    }
    if (empty($icon)) {
        $icon = $config['default_icon'];
    }

    // 安全转义
    $safeUrl         = htmlspecialchars($url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    $safeTitle       = safeTruncate($title, $config['max_title_length']);
    $safeDescription = safeTruncate($description, $config['max_desc_length']);
    $safeIcon        = htmlspecialchars($icon, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // 构建卡片 HTML（使用 data-* 属性保留原始信息，但不用于执行）
    $html  = '<div class="link-card" data-url="' . $safeUrl . '">' . "\n";
    $html .= '    <a href="' . $safeUrl . '" target="_blank" rel="noopener noreferrer" class="link-card-link">' . "\n";

    // 图标区块
    if (!empty($safeIcon)) {
        $html .= '        <span class="link-card-icon">';
        $html .= '<img src="' . $safeIcon . '" alt="icon" width="24" height="24" loading="lazy">';
        $html .= "</span>\n";
    }

    // 文字区块
    $html .= '        <span class="link-card-text">' . "\n";
    $html .= '            <strong class="link-card-title">' . $safeTitle . "</strong>\n";
    $html .= '            <span class="link-card-desc">' . $safeDescription . "</span>\n";
    $html .= '        </span>' . "\n";
    $html .= '    </a>' . "\n";
    $html .= "</div>\n";

    return $html;
}

/**
 * 快速生成一个指向爱游戏官网的卡片
 */
function renderDefaultAiyouxiCard(): string
{
    return renderLinkCard(
        'https://i-gameapp.com.cn',
        '爱游戏',
        '爱游戏 - 发现更多精彩游戏内容，与玩家一起交流。',
        '',
        []
    );
}

// --- 示例用法（可直接取消注释测试） ---
// echo renderDefaultAiyouxiCard();
// echo renderLinkCard('https://i-gameapp.com.cn', '爱游戏', '最新游戏资讯');
// echo renderLinkCard('', '', '', '', ['max_title_length' => 20]);