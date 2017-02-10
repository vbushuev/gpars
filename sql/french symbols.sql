update xr_g_product set
g_description = replace(g_description,'Ã©','é'),description = replace(description,'Ã©','é')
,g_description = replace(g_description,'Ã¨','è'),description = replace(description,'Ã¨','è')
,g_description = replace(g_description,'Ã','à'),description = replace(description,'Ã','à')
where shop = 'brandalley';

update xr_posts set
post_content = replace(post_content,'Ã©','é'),post_title = replace(post_title,'Ã©','é')
,post_content = replace(post_content,'Ã¨','è'),post_title = replace(post_title,'Ã¨','è')
,post_content = replace(post_content,'Ã','à'),post_title = replace(post_title,'Ã','à')
where post_type = 'product';


update xr_terms set
name = replace(name,'Ã©','é')
,name = replace(name,'Ã¨','è')
,name = replace(name,'Ã','à')
where 1;
