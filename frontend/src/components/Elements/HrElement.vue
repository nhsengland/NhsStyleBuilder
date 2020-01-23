<template>
  <div class="hr-element z-element z-component">
    <a class="preview">
      <hr>
    </a>
    <div class="inline-element-control">
      <a class="icon icon_only _icon-remove element-remove--btn" @click="removeThis"></a>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
export default {
  name: 'hr-element',

  mixins: [
    _mixins.element,
  ]
}
</script>

<style lang="postcss" scoped>
@import '../../sass/Elements/HrElement/scoped';
</style>