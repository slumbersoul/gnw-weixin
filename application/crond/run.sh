##!/bin/bash
#http://nightsailer.com/2010/01/29/641.html
#export APPLICATION_ENV=development
#export APPLICATION_ENV=lddevelopment
export APPLICATION_ENV=production
# 轮询执行PHP
DIR=`dirname $0`
#####################
## 运行后台程序
#
# 1. 通过lock文件, 如果进程存在且不匹配, kill原PID
# 2. 如果PID不存在, 生成自己的PID
# 3. 如果PID匹配,结束自己
# 4. 循环PHP进程
#
# run.sh {base64} {time} {kill}
#
#####################
if [ -z $1 ];then
  # 运行内容为空
  exit 0;
fi

PHP_RUN="$1"
PHP_TIME="$2"
PHP_ON="$3"
if [[ "$PHP_TIME" =~ "^[0-9]+$" ]];then
  SHELL_TIME="$PHP_TIME"
else
  #默认1分钟
  PHP_TIME=60
  SHELL_TIME=60
fi

SHELL_LOCK="${DIR}/lock/${PHP_RUN}.lock"
SHELL_RUN=''
#当前执行的shell pid
SHELL_PID=0
#保持原shell的运行
SHELL_HOLD=0

if [ -f $SHELL_LOCK ];then
  #存在lock文件, 判断是否匹配现有参数
  line=1
  while read row
  do
    if [ "$line" -eq 1 ];then
      if [[ "$row" =~ "^[0-9]+$" ]];then
        SHELL_PID=$row
      fi
    fi
    if [ "$line" -eq 2 ];then
      SHELL_RUN=$row
    fi
    if [ "$line" -eq 3 ];then
      if [[ "$row" =~ "^[0-9]+$" ]];then
        SHELL_TIME=$row
      fi
    fi

    line=$((${line}+1))
  done < $SHELL_LOCK

  ## 判断SHELL_PID是否存在
  if [ "`expr $SHELL_PID + 0`" != "" -a -d "/proc/$SHELL_PID" ];then
    ## 关闭命令
    if [ "$PHP_ON" = "off" ];then
      kill -9 "$SHELL_PID"  
      rm $SHELL_LOCK
      echo "close $SHELL_PID !"
      exit 0;
    fi
    
    ## 判断轮询时间 和 PHP_RUN、SHELL_RUN
    if [ "$SHELL_TIME" -eq "$PHP_TIME" ];then
      if [ "$SHELL_RUN" = "$PHP_RUN" ];then
        SHELL_HOLD=1
      fi
    fi
    ## 如果完全匹配结束自己, 反之结束原有进程
    if [ "$SHELL_HOLD" -eq 1 ];then
      exit 0;
    else
      kill -9 "$SHELL_PID"
      rm $SHELL_LOCK
      echo "remove old shell $SHELL_PID ..."
    fi

  else
    ## PID不存在, 删除lock文件
    rm $SHELL_LOCK
    echo "remove $SHELL_LOCK ."
  fi

fi;
#END lock

#是该结束鸟
if [ "$PHP_ON" = "off" ];then
  exit 0;
fi;

#写入SHELL_LOCK
#####################
## lock文件
# 第一行: 当前shell PID
# 第二行: base64编码的函数
# 第三行: php轮询间隔
#####################
echo "$$" > "$SHELL_LOCK"
echo "$PHP_RUN" >> "$SHELL_LOCK"
echo "$PHP_TIME" >> "$SHELL_LOCK"


DAEMON="$DIR/run.php $PHP_RUN"
while true; do
/usr/local/bin/php $DAEMON
echo "php $DAEMON"
sleep $SHELL_TIME 
done


