##Text File Generator

###Questions?

Feel free to [open an issue](https://github.com/geditdk/text-file-generator/issues) on this repository (not on your fork!) if you have any questions about the task.

###Task

Fork this repository and commit any source/files to your fork.

You must generate 100 text files with the following information contained within them:
```XML
<doc>
	<field>Source</field>
	<value>xxxxx</value>
</doc>
```

`xxxxx` must be randomly generated with one of the following values:
```
A12345
B98765
C11111
D99999
E15975
```
The files must be stored in the following location:

Windows: `C:\XML\SystemFix\Red`

or

Linux: `/opt/xml/SystemFix/Red`

The files must be named with a new UUID e.g. `5b72c025-c8e9-45eb-940f-7f4909cfe607.txt`

####Bonus:
Can you run your application in a docker container and persist the files?

Upload your `Dockerfile` to your fork also.
