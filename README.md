===========
CodeIgniter - EllisLab's Open Source PHP Framework
===========

===========
分支2.1.3_memcache_session改进（基于2.1.3）
===========

（1）增加php memcache扩展支持

（2）session改为存入memcache、并且降低重复发送cookies的问题

===========
未解决问题（也是CI SESSION使用DB的毛病）
===========

设置一次session就写一次缓存（或数据库），压力测试下效率会比较差。

===========
更改历史
===========

（GitHub）https://github.com/HorseLuke/CodeIgniter/commit/1e58060de414e5250390bff2d847aa09a1d6862d

（Google code SVN）https://code.google.com/p/horseluke-code/source/detail?r=225

===========
测试访问url
===========
http://127.0.0.1/nnnnnnnnnnn/index.php/session_test

（此分支为了快速演示、更改了application/config/config.php中的encryption_key，请注意！）
