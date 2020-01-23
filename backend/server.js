var express = require('express');
var app = express();
var cors = require('cors');
const path = require('path');
var bodyParser = require('body-parser');

// must specify options hash even if no options provided!
var phpExpress = require('./PHPExpress')
var phpExpressEngine = new phpExpress({

  // assumes php is in your PATH
  binPath: 'php'
});

// set view engine to php-express
app.set('views', '.');
app.engine('php', phpExpressEngine.engine);
app.set('view engine', 'php');

app.use(cors());
app.use(bodyParser.json({ limit: "50mb" })); // support json encoded bodies
app.use(bodyParser.urlencoded({ limit: "50mb", extended: true, parameterLimit: 50000 })); // support encoded bodies

var staticPath = path.posix.join('/', 'static');
app.use(staticPath, express.static('./static'));
var cssPath = path.posix.join('/', 'css');
app.use(cssPath, express.static('./css'));

// routing all .php file to php-express
app.all(/.*/, phpExpressEngine.router);

var server = app.listen(3000, function () {
  var host = server.address().address;
  var port = server.address().port;
  console.log('PHPExpress app listening at http://%s:%s', host, port);
});
