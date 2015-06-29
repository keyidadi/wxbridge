微信JSON API使用说明

1． 扫一扫关注测试公众号
二维码
2． 注册设备devid
在公众号中发送消息，格式为reg:<devid>，即可将您的微信号绑定到指定的设备上。
此操作成功后公众号将回复Reg OK:<devid>。
例如设备devid是AABBCCDDEEFF，发送的信息为reg:AABBCCDDEEFF
3． 用户通过微信API向设备发送消息
a)         在公众号中发送消息，格式为#<devid>:<msg>，此操作成功后公众号将回复Recv OK：<devid>, <msg>
例如#AABBCCDDEEFF:Hello World!
b)         设备通过GET/POST方法访问API可以得到前面发送的消息。
GET方法:
URL:          http://keyidadi.tk/recv/DEVID/MAX
POST方法:
URL:          http://keyidadi.tk/recv/
Body:        {“devid”:”DEVID”, “max”:”MAX” }
 
参数说明：
DEVID      (必需)       为设备devid
MAX          (可选)       一次最多取MAX条消息
 
返回值：{“ret”:”RET”, ”result”:[Message Array], ”msg”:”Error Message”}
ret                                错误代码，如果不为0则发生错误，错误信息由msg指出。
result                           消息字符串数组，按接收先后顺序排列。
msg                             错误信息，成功返回没有此字段。
4． 设备通过微信API向用户发送消息
a)         设备通过GET/POST方法访问API可以向用户发送消息。
GET方法:
URL:          http://keyidadi.tk/send/DEVID/MSG
POST方法:
URL:          http://keyidadi.tk/send/
Body:        {“devid”:”DEVID”, “msg”:”MSG” }
 
参数说明：
DEVID      (必需)       为设备devid
MSG          (必需)       待发送的消息内容
 
返回值：{“ret”:”RET”, “msg”:”MSG”}
ret                                错误代码，如果不为0则发生错误，错误信息由msg指出。
msg                             错误信息，成功返回没有此字段。
 
注意：GET方法发送的消息需要做URL编码，切记。
 
b)         上面操作成功完成后，公众号中会收到消息(参数msg中内容)。
5． 注销设备绑定
在公众号中发送消息，格式为unreg:<devid>，即可解绑。成功操作返回Unreg OK:<devid>。
