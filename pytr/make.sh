\rm -rf dist
pyinstaller --onefile pytr.py --name pytr
#pyinstaller --key 20230313pytr --onefile pytr.py --name pytr
#pyinstaller --key 20230103aBK --onefile BkOrderDo.py --name BkOrder

cp dist/pytr .
cp pytr /home/HTS_V1/bin/
