<?php

function get_playtime($post_id)
{
  $playtime = get_field('acf_playtime', $post_id);

  if (!$playtime) {
    return [
      'formatted' => '00:00',
      'iso'       => 'PT0S'
    ];
  }

  // 時間・分・秒を分解（`00:12:01` や `01:00:20` を想定）
  list($hours, $minutes, $seconds) = explode(':', $playtime);

  $hours   = (int) $hours;
  $minutes = (int) $minutes;
  $seconds = (int) $seconds;

  // `datetime` 用 ISO 8601 フォーマット（PTxxHxxMxxS）
  $iso_playtime = 'PT';
  if ($hours > 0) $iso_playtime .= "{$hours}H";
  if ($minutes > 0) $iso_playtime .= "{$minutes}M";
  if ($seconds > 0) $iso_playtime .= "{$seconds}S";

  if ($iso_playtime === 'PT') {
    $iso_playtime = 'PT0S';
  }

  // `MM:SS` 形式に統一（1時間以上の場合は `H:MM:SS`）
  if ($hours > 0) {
    $formatted_playtime = sprintf("%d:%02d:%02d", $hours, $minutes, $seconds);
  } else {
    $formatted_playtime = sprintf("%02d:%02d", $minutes, $seconds);
  }

  return [
    'formatted' => $formatted_playtime,
    'iso'       => $iso_playtime
  ];
}


?>