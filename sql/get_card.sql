SELECT c.* FROM
xr_deals d

join xr_garan24_user_cardref uc on uc.user_id=d.customer_id
join xr_garan24_cardrefs c on c.id = uc.card_ref_id
where d.internal_order_id = 31771
