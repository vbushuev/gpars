select u.id,u.user_email,um.meta_value as `phone`,u.id as `customer_id`
 ,fio.value_data as `fio_middle`
 ,bd.value_data as `fio_birthday`
 ,passport.value_data as `passport`
from xr_users u
 join xr_usermeta um on u.id = um.user_id and um.meta_key='billing_phone'
 left outer join xr_garan24_usermeta fio on u.id = fio.user_id and fio.value_key='fio_middle'
 left outer join xr_garan24_usermeta bd on u.id = bd.user_id and bd.value_key='fio_birthday'
 left outer join xr_garan24_usermeta passport on u.id = passport.user_id and passport.value_key='passport'
