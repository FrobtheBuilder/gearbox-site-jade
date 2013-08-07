all:
	jade src/ -o build;
	cp build/* .;


build:
	jade src/ -o build;

clean:
	rm build/*;
	rm *.html;
