
# Clip 介绍
Clip是一款名字服务工具（C/S架构）思路与DNS近似，通过String方式来管理机器资源,其中IP与String的关系保存在Sever端，Client端通过SDK可以批量执行命令、批量发布文件、扫描业务存活端口和遍历String树形关系功能等。Clip在海量运维场景下可以提升我们的工作效率，譬如目前还可以经常看到程序写死IP的情况当IP故障需要重新发布程序配置解决，这时可以通过Clip String方式替代写死IP的情况，当IP故障时只需修改Server端的String对应IP关系来减少发布次数提升我们效率。

以下你将会了解到
1. 安装文档
2. sdk功能介绍
3. server端API介绍

# 安装文档
 http://blog.puppeter.com/read.php?7

# Client端 SDK 介绍
<pre>
# clip 
Usage: 
  clip COMMAND [ARGS...]
  clip help COMMAND

Options:
  -h, --help  show this help message and exit

Commands:
  scan      scan command                     # 扫描机器列表
  cstring   cstring command                  # IP 与 String关系转换
  ssh       ssh command                      # 远程执行系统命令
  scp       scp command                      # 拷贝本地文件到远程 && 拷贝远程文件到本地
  tree      tree command                     # 树形结构显示名字服务列表
  history   history command                  # 查询历史执行的命令
  import    import command                   # 导入IP与名字服务对应关系
  lt        lt command                       # 本地IP列表执行远程命令 && 考本文件到远程
  version   version command                  # 显示版本信息
  help (?)  give detailed help on a specific sub-command
  </pre>

每个子命令会有相应的帮助信息，譬如clip cstring 
<pre>
clip cstring 
Usage: clip cstring [OPTIONS] param...

Cstring(Clip String)Convert IP to String or String to IP

Options:
  -h, --help            show this help message and exit
  -q Q, --query_string=Q
  -d, --dryrun          
  -i I, --ip=I          
  -c, --count           
  -l L, --limit=L       
  -o, --log_disable     
  -r R, --remove_ip=R   
  -a A, --append=A      
  -j J, --join=J        


-----------EXAMPLE--------

#### Action ####
# clip cstring --query_string (-q) *-test-pc-*
# clip cstring --ip (-i) 10.149.19.84
# cliy cstring --query_string (-q) *-test-pc-*,*-docker-*-*
# clip cstring --query_string (-q) sz-qzone-qzoneini-access5 --limit (-l) 10,15
# clip cstring --query_string *-test-*-* --append (-a) 192.168.0.1,192.168.0.2
# clip cstring --query_string (-q) *-test-*-* --remove_ip (-r) 192.168.0.1,192.168.0.6
# clip cstring --query_string *-test-*-* --join (-j)  "("|" "," "\n" "space" "json")"
#### Switch ###
# clip cstring --query_string (-q) *-test-pc-* --dryrun (-d)
# clip cstring --query_string (-q) *-test-pc-* --count (-c)
# clip cstring --query_string (-q) sz-qzone-qzoneini-access5 --log_disable (-o)

</pre>
clip端由python开发，以下为代码结构
<pre>
.
|-- clip                   # 执行文件
|-- conf                   # 配置文件
|   |-- clip.ini  
|   |-- cstring.ini
|   |-- framework.ini
|   |-- history.ini
|   |-- import.ini
|   |-- lt.ini
|   |-- property.ini
|   |-- scan.ini
|   |-- scp.ini
|   |-- ssh.ini
|   |-- tree.ini
|   `-- version.ini
|-- lib                    # 库文件
|   |-- __init__.py
|   |-- subcommand.py
|   |-- tiny_expect.exp
|   `-- tiny_expect_scp.exp
`-- plugin                 # 插件目录
    |-- __init__.py
    |-- plugin_base.py
    |-- plugin_cstring.py
    |-- plugin_history.py
    |-- plugin_import.py
    |-- plugin_lt.py
    |-- plugin_scan.py
    |-- plugin_scp.py
    |-- plugin_ssh.py
    |-- plugin_tree.py
    `-- plugin_version.py
    </pre>

# Server端 API 介绍
## 1.接口功能：测试 （test）
<pre>
返回： ping ok
curl IP/index.php/Clip/test
</pre>


## 2.接口功能：显示树形结构 （/index.php/Clip/api_get_tree）
<pre>
参数        含义                           类型       是否必传   参数内容
cstring     获取指定名字服务的树形结构     string        Y            
operator    操作权限                       string        Y        default|guest|admin
signature   url签名                        string        Y        php案例：md5($input['cstring']."-".$key."-".date('H')) 

返回：
参数   含义
ret    0正常，非0失败    
data   返回内容

curl 用例
curl "IP/index.php/Clip/api_get_tree?cstring=*-csg-*-*&operator=guest&signature=8f6857d4cc9681f4d17a242b44d23c72"
</pre>

## 3.接口功能：获取IP对应名字服务关系(/index.php/Clip/api_version1)
<pre>
参数        含义                           类型       是否必传   参数内容
parameter   参数类型                       string        Y        "getip","getcstring","mgetip","mgetcstring"
cstring     获取指定名字服务的树形结构       string        Y            
operator    操作权限                       string        Y        default|guest|admin
signature   url签名                        string        Y        php案例：md5($input['cstring']."-".$key."-".date('H')) 
format      返回格式                       string        N        text(默认)|json

返回：
参数   含义
ret    0正常，非0失败    
data   返回内容

curl 用例
curl "IP/index.php/Clip/api_version1?parameter=getcstring&cstring=*-cls-*-*&operator=guest&signature=06588845bdad76e1a143831e9a970661"

</pre>

## 4.接口功能：修改flag状态(index.php/Clip/clip_updateFlag)
<pre>
参数        含义                           类型       是否必传   参数内容
IP          参数类型                      string        Y        
flag        机器状态                      string        Y        1（正常）| 2（下线）| 8（故障）           

返回：
参数   含义
ret    0正常，非0失败    
data   返回内容

curl 用例
curl "IPindex.php/Clip/clip_updateFlag?ip=192.168.1.1&flag=2&signature=06588845bdad76e1a143831e9a970661"
</pre>


## 5.接口功能：修改flag状态(index.php/Clip/clip_register)
<pre>


</pre>
