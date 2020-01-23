<template>
  <div class="image-field-collection z-field-collection">
    <span>
    {{imgName}}
    </span>
    <transition name="slide-fade">
      <div v-if="showBody">
        <br>
        <br>
        <input
            placeholder="Type the image url"
            @blur="updateObj('ImageSrc', 'contents', $event.target.value)"
            @keyup.enter="updateObj('ImageSrc', 'contents', $event.target.value)"
            :value="Obj.contents.ImageSrc"
            class="inline-input" />
        <br>
        <br>
        <input
            placeholder="Type the link if any"
            @blur="updateObj('ImageUrl', 'contents', $event.target.value)"
            @keyup.enter="updateObj('ImageUrl', 'contents', $event.target.value)"
            :value="Obj.contents.ImageUrl"
            class="inline-input" />
          <br />
          <br />
          <input
            placeholder="Image Title"
            @blur="updateObj('ImageTitle', 'contents', $event.target.value)"
            @keyup.enter="updateObj('ImageTitle', 'contents', $event.target.value)"
            :value="Obj.contents.ImageTitle"
            class="inline-input" />
          <br />
          <br />
          <input
            placeholder="Alternative text"
            @blur="updateObj('ImageAlt', 'contents', $event.target.value)"
            @keyup.enter="updateObj('ImageAlt', 'contents', $event.target.value)"
            :value="Obj.contents.ImageAlt"
            class="inline-input" />
      </div>
    </transition>
    <br>
    <br>
    <div class="kd_columns kd_columns-3">
      <div class="kd_col">
        <span class="icon material-icons dragme float-left">
          open_with
        </span>
      </div>
      <div class="kd_col" style="text-align: center;">
        <a
          class="bg-transparent icon_block--icon-position-center icon icon_only material-icons"
          @click = "showBody = !showBody"
          :title="showBody ? 'Click to hide content' : 'Click to edit content'"
          >{{showBody ? 'vertical_align_top' : 'edit'}}
        </a>
      </div>
      <div class="kd_col">
        <a class="icon icon_inline material-icons float-right"
          @click="removeThis">delete_outline</a>
      </div>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
export default {
  name: 'image-field-collection',

  mixins: [
    _mixins.element,
  ],
  computed: {
    imgName() {
      let filename = null;
      const src = this.Obj.contents.ImageSrc;
      if(src) {
        let tmp = src.replace(/\?.*/i, '');
        filename = tmp.substring(tmp.lastIndexOf('/')+1);
      }
      if(filename.length < 1) {
        filename = 'Image';
      }
      return filename;
    }
  },
  data() {
    return {
      showBody: false,
    }
  }
}
</script>