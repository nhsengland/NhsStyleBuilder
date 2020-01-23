# Simple debounce, an alternative to loadash

Inspired by https://medium.com/vuejs-tips/tiny-debounce-for-vue-js-cea7a1513728

It works with any methods, not watcher only. It accepts previouse timeoutID to accurately cancel previous timeout.

### Example:
```javascript
computed: {
  data() {
    return {
      debouncerId: null
    }
  },
  fullName() {
    this.debouncerId = debounce(function(data) {
      axios.post(vm.serviceUrl, data)
      .then(function (response) {
        vm.fullname = response.fullname;
      })
      .catch(function (error) {
      });
    }, 500)(this.debouncerId);
  };
}
```