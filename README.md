# Clip
Clip 自动化运维工具C/S架构具体使用见（http://blog.puppeter.com/read.php?7）。 其中server端为php开发使用了doitphp框架，client端为Python开发

# Client
clip端由python开发，以下为代码结构
<pre>
.
|-- clip                       #命令工具
|-- conf
|   |-- clip.ini               # 主配置文件
|   |-- cstring.ini            
|   |-- framework.ini
|   |-- history.ini
|   |-- import.ini
|   |-- lt.ini
|   |-- property.ini
|   |-- scan.ini
|   |-- scp.ini
|   |-- ssh.ini
|   |-- template.ini
|   `-- tree.ini
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
    |-- plugin_property.py
    |-- plugin_scan.py
    |-- plugin_scp.py
    |-- plugin_ssh.py
    `-- plugin_tree.py
    </pre>


[![ScreenShot](https://raw.github.com/GabLeRoux/WebMole/master/ressources/WebMole_Youtube_Video.png)](http://youtu.be/vt5fpE0bzSY)
