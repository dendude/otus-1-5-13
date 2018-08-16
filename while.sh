#!/bin/bash
x=0
while [ $x -lt 5 ]
do
./socket-client.php --message="Some test"
x=$(( $x + 1 ))
done
