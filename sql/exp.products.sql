SELECT `id`, `timestamp`, `shop`, `shop_url`, `brand`, `title`, `categories`, `description`, `original_price`, `regular_price`, `currency`, `sku`, `url`, `options`, `images`, `g_sku`, `g_title`, `g_description`, `g_categories`, `g_categories_id`, `status`, g_title REGEXP '[a-z]+' as Dictionary_mistake
into outfile '/var/www/html/gpars/logs/export/products-'+FROM_UNIXTIME(UNIX_TIMESTAMP(),'%Y-%D-%M')+'.csv'
FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '"'
  LINES TERMINATED BY '\n'
FROM `xr_g_product` WHERE 1;


#checking
SELECT
title,
g_title,
(g_title regexp '[a-z]+') - (g_title regexp 'end\-on\-end|v\-')  as latin,
description,
g_description
FROM `xr_g_product`;
