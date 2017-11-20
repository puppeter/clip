# Clip 介绍
Clip是一款名字服务工具（C/S架构）思路与DNS近似，通过String方式来管理机器资源,其中IP与String的关系保存在Sever端，Client端通过SDK可以批量执行命令、批量发布文件、扫描业务存活端口和遍历String树形关系功能等。Clip在海量运维场景下可以提升我们的工作效率，譬如目前还可以经常看到程序写死IP的情况当IP故障需要重新发布程序配置解决，这时可以通过Clip String方式替代写死IP的情况，当IP故障时只需修改Server端的String对应IP关系来减少发布次数提升我们效率，关于clip更多信息见http://blog.puppeter.com/read.php?7。 

# sdk 介绍
<pre>
# clip 
Usage: 
  clip COMMAND [ARGS...]
  clip help COMMAND

Options:
  -h, --help  show this help message and exit

Commands:
  scan      scan command                     # 扫描机器列表
  cstring   cstring command                  # ip 与 string关系转换
  ssh       ssh command                      # 远程执行系统命令
  scp       scp command                      # 拷贝本地文件到远程 && 拷贝远程文件到本地
  tree      tree command                     # 树形结构显示名字服务列表
  history   history command                  # 查询历史执行的命令
  import    import command                   # 导入ip与名字服务对应关系
  lt        lt command                       # 本地ip列表执行远程命令 && 考本文件到远程
  version   version command                  # 显示版本信息
  help (?)  give detailed help on a specific sub-command
  </pre>

# Client结构
clip端由python开发，以下为代码结构
<pre>
.
|-- clip    #执行文件
|-- conf
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
|-- lib
|   |-- __init__.py
|   |-- subcommand.py
|   |-- tiny_expect.exp
|   `-- tiny_expect_scp.exp
`-- plugin
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

