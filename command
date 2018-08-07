// install forever
npm install -g forever

// convert video service
// chờ 10s để chạy lại
// tên để restart "video_converter"


// luồng 1
// log convert_log.txt
// uid video_converter
forever start -l /dev/null -o /Users/hocvt/Documents/webroot/unibee/convert_log.txt -a --uid "video_converter" --spinSleepTime 10000 -c /Applications/MAMP/bin/php/php5.6.10/bin/php /Users/hocvt/Documents/webroot/unibee/artisan videos:convert
// luồng 2
// log convert_log_02.txt
// uid video_converter_02
forever start -l /dev/null -o /Users/hocvt/Documents/webroot/unibee/convert_log_02.txt -a --uid "video_converter_02" --spinSleepTime 10000 -c /Applications/MAMP/bin/php/php5.6.10/bin/php /Users/hocvt/Documents/webroot/unibee/artisan videos:convert



forever start -l /dev/null -o /var/www/dev.unibee.org/convert_log.txt -a --uid "video_converter" --spinSleepTime 10000 -c /usr/local/php5530_fpm/bin/php /var/www/dev.unibee.org/artisan videos:convert



// UBCLASS
// luồng 1
// log convert_log.txt
// uid ub_video_converter
forever start -l /dev/null -o /var/www/ubclass.com/convert_log.txt -a --uid "ub_video_converter" --spinSleepTime 10000 -c /usr/local/php5530_fpm/bin/php /var/www/ubclass.com/artisan videos:convert


// Quochoc
// luồng 1 chạy dowload video
// log convert_log.txt
// uid quochoc_download_video_youtube

forever start -l /dev/null -o /var/www/quochoc.vn/download_video.txt -a --uid "quochoc_download_video_youtube" --spinSleepTime 10000 -c /usr/local/php5530_fpm/bin/php /var/www/quochoc.vn/artisan youtube:download
