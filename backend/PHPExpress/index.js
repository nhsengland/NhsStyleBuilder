var PHPExpress = function (opts) {
    opts = opts || {};

    this.binPath = opts.binPath || '/usr/bin/php',
    this.runnerPath = opts.runnerPath || (__dirname + '/page_runner.php');
    this.maxBuffer = opts.maxBuffer || 1024 * 1024;

    // default to true for easier PHP debugging
    this.displayErrors = typeof opts.displayErrors === 'undefined' ? true : opts.displayErrors;

    this.engine = require('./engine').bind(this);
    this.router = require('./router').bind(this);
};

module.exports = PHPExpress;
