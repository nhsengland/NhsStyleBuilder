var path = require('path'),
    util = require('util'),
    querystring = require('querystring'),
    child_process = require('child_process'),
    fs = require('fs');

var engine = function (filePath, opts, callback) {
    var binPath = this.binPath,
        runnerPath = this.runnerPath,
        displayErrors = this.displayErrors,
        maxBuffer = this.maxBuffer,

        method = opts.method || 'GET',
        get = opts.get || {},
        post = opts.post || {},

        query = opts.query || querystring.stringify(get),
        body = opts.body || JSON.stringify(post),

        processOptions = {
            env: {
                REQUEST_METHOD: method,
                CONTENT_LENGTH: body.length,
                QUERY_STRING: query
            }
        };

    if (maxBuffer) {
        processOptions.maxBuffer = maxBuffer
    }

    if(body) {
      var dataFilePath = __dirname + '/../api/data.tmp';
      var logStream = fs.createWriteStream(dataFilePath, {'flags': 'w'});
      // Generate the ftp file.
      // logStream.write(body);
      logStream.end(body);
      body = querystring.stringify({post_path: path.resolve(dataFilePath)});
    }
    var command = util.format(
        '%s %s %s %s',
        (body ? util.format('echo %s | ', body) : '') + binPath,
        runnerPath,
        path.dirname(filePath),
        filePath
    );

    child_process.exec(command, processOptions, function (error, stdout, stderr) {
        if (error) {

            // can leak server configuration
            if (displayErrors && stdout) {
                callback(stdout);
            } else {
                callback(error);
            }
        } else if (stdout) {
            callback(null, stdout);
        } else if (stderr) {
            callback(stderr);
        } else {
            callback(null, null);
        }
    });
};

module.exports = engine;
