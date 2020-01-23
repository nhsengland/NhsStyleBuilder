module.exports = function debounce (fn, delay) {
  var timeoutID = null
  return function (preTimeoutID) {
    clearTimeout(preTimeoutID)
    var args = arguments
    var that = this
    timeoutID = setTimeout(function () {
      fn.apply(that, args)
    }, delay)
    return timeoutID;
  }
}