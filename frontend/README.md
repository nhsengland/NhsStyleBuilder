# builder

> Pwa project for constructing layout blocks for nhskahootz

Features:
- Global Options - 2
- Draggable, rows and elements - 2 (v)
- Pages store - 3
- Dependancy on element's options.
- File upload (x)
- Rich elements - 1+ (v)
  - seperator
  - text (html)
  - image
- Generating html and reverse to json - 1 (v)
- Remove Button with confirmation
- remove generated html once buildScript gets an update
- beautify generated html so users can read and modify html
- when buildScripts copied back, system should compare and refuse copy-back if buildScripts no longer matches generated html
- Page template (pre-built buildScripts)
- Page template can be added as a new row
- Allow front-end user to create Page template
- Options to adjust image
- Display label in options (v-select), the selected value is now showing raw value. (v)
- copy-back html (v)
- include projectOptions in buildScripts, as well as copy back.

1. Rich Elements (more elements)
  - ~~iFrame (applet)~~
  - ~~Accordion~~
  - ~~InfoCard~~
  - ~~Slider (Text Slide or Image Slide)~~
  - Responsive
2. ~~Drag-n-drop, rows and elements (v)~~
3.	~~Copy back html (v)~~
4.	~~Options help texts~~
5.	~~Global Options (e.g. primary color, secondary color, etc..)~~
---------------
6.	Page storage
7.	Undo button
8.	Page bin
9.	Dependency of elements' options. Some options rely on other options.
10.	"Remove button" with confirmation
11.	Beautify generated html so user can read and modify
12.	Copy back html with a comparison which tells if builder can still be used.
13.	Templating
14.	User-define templates.
15.	Options to adjust image's dimension
16.	~~Display label, instead of raw value, in options selection box.~~
17. Color picker, swatches

## Build Setup

``` bash
# make sure the relevant npm is on
nvm use 8.8.1

# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build

# build for production and view the bundle analyzer report
npm run build --report
```

For detailed explanation on how things work, checkout the [guide](http://vuejs-templates.github.io/webpack/) and [docs for vue-loader](http://vuejs.github.io/vue-loader).
