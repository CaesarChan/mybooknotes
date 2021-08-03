push:
	git push origin master

serve:
	mkdocs serve

publish:
	# if conflict, delete gh-pages branch and site dir
	git push origin master
	mkdocs gh-deploy --ignore-version

clean:
	rm -rf out/uml	

oss:
	ossutilmac64 cp -r uml oss://hugopost