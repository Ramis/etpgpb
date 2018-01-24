<?php

namespace common\models;

use Yii;
use yii\helpers\VarDumper;

class Log
{

    /**
     * Выборка данных для показа в табличной форме
     *
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public static function getStatisticsLog (int $limit, int $offset):array {

        try {
            $sql = <<<SSQQLL
                WITH l_url_to AS (
                    SELECT
                        u.ip,
                        u.url_to AS last_url_to
                    FROM
                        log_on_urls AS u
                    INNER JOIN (
                        SELECT
                            ip,
                            MAX (date_time) AS date_time
                        FROM
                            log_on_urls
                        GROUP BY
                            ip
                    ) AS date_time_min ON u.ip = date_time_min.ip
                    AND date_time_min.date_time = u.date_time
                ),
                 f_url_from AS (
                    SELECT
                        u.ip,
                        u.url_from AS first_url_from
                    FROM
                        log_on_urls AS u
                    INNER JOIN (
                        SELECT
                            ip,
                            MIN (date_time) AS date_time
                        FROM
                            log_on_urls
                        GROUP BY
                            ip
                    ) AS date_time_max ON u.ip = date_time_max.ip
                    AND date_time_max.date_time = u.date_time
                ) SELECT
                    os.ip as ip,
                    os.browser as browser,
                    os.os as os, 
                    ip_url_unique.url_cnt_unique as url_cnt_unique,
                    f_url_from.first_url_from as first_url_from,
                    l_url_to.last_url_to as last_url_to
                FROM
                    log_on_os AS os
                INNER JOIN (
                    SELECT
                        ip,
                        COUNT (*) AS url_cnt_unique
                    FROM
                        (
                            SELECT
                                ip,
                                url_to,
                                COUNT (*)
                            FROM
                                log_on_urls
                            GROUP BY
                                ip,
                                url_to
                            UNION
                                SELECT
                                    ip,
                                    url_from,
                                    COUNT (*)
                                FROM
                                    log_on_urls
                                GROUP BY
                                    ip,
                                    url_from
                        ) AS group_url
                    GROUP BY
                        ip
                ) AS ip_url_unique ON ip_url_unique.ip = os.ip
                INNER JOIN f_url_from ON f_url_from.ip = os.ip
                INNER JOIN l_url_to ON l_url_to.ip = os.ip
                LIMIT :limit :offset
SSQQLL;

            $rows = Yii::$app->getDb()->createCommand($sql)->bindParam(':limit', $limit)->bindParam(':offset', $offset)->queryAll();
        } catch (\Exception $e) {
            $rows = [];
        }

        return $rows;

    }

}
