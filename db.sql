-- =========================================================
-- CastMap 案件テーブル 作成用SQL
-- 使い方: phpMyAdmin の「SQL」タブにこの内容を貼って実行する
--   （ローカルXAMPP / さくら 共通。さくらではDBは管理画面で作成済みのことが多い）
-- =========================================================

-- ローカルで一からDBを作る場合はこの2行も実行（さくらでは不要なことが多い）
CREATE DATABASE IF NOT EXISTS castmap DEFAULT CHARACTER SET utf8mb4;
USE castmap;

-- 案件（ブッキング）テーブル
CREATE TABLE IF NOT EXISTS bookings (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(100) NOT NULL,                 -- 案件名
  talent_name VARCHAR(50)  NOT NULL,                 -- タレント名
  place       VARCHAR(100) DEFAULT NULL,             -- 場所
  start_at    DATETIME     NOT NULL,                 -- 開始日時
  end_at      DATETIME     DEFAULT NULL,             -- 終了日時
  status      VARCHAR(10)  NOT NULL DEFAULT '打診中', -- 打診中 / 仮 / 確定 / 完了
  memo        TEXT         DEFAULT NULL,             -- メモ
  created_at  DATETIME     DEFAULT CURRENT_TIMESTAMP -- 登録日時（自動）
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 動作確認用のサンプルデータ（任意。不要なら消してOK）
INSERT INTO bookings (title, talent_name, place, start_at, end_at, status, memo) VALUES
('音楽番組 収録', '春野 凜', '東京 スタジオA', '2026-07-10 13:00:00', '2026-07-10 17:00:00', '確定', '衣装は先方手配'),
('地方イベント 出演', '夏目 悠真', '大阪 ホール', '2026-07-12 18:00:00', '2026-07-12 20:00:00', '仮', '交通費別途'),
('CM 撮影 オファー', '橘 美月', '福岡 未定', '2026-07-15 10:00:00', NULL, '打診中', '日程調整中');
