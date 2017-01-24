select * from  xr_g_product where shop='ctshirts' and status = 'translated';
update xr_g_product set status = '2remove'  where shop='ctshirts' and status = 'translated';
SELECT STATUS,COUNT(*) FROM `xr_g_product` where shop='ctshirts' GROUP BY status;

select * from  xr_g_product where shop='ctshirts' and updated is null;
update xr_g_product set status = '2remove' where shop='ctshirts' and updated is null;
update xr_g_product set status = 'new' where shop='ctshirts' and status='translated';



select * from xr_g_products where shop='ctshirts';
