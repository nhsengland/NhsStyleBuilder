<template>
  <div class="text-field-collection z-field-collection">
    <span>
    {{txtTitle}}
    </span>
    <transition name="slide-fade">
      <div v-if="showBody">
        <br>
        <br>
        <vue-ckeditor
          placeholder="Type the text"
          @blur="updateObj('Text', 'contents', $event._.data)"
          :config="config"
          :value="Obj.contents.Text" />
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
  name: 'text-field-collection',

  mixins: [
    _mixins.element,
  ],
  computed: {
    txtTitle() {
      const text = this.Obj.contents.Text;
      let tmp = text.replace(/(<([^>]+)>)/ig,"").replace('&nbsp;', ' ');
      return tmp.substring(0, 18) + '...';
    },
  },
  data() {
    return {
      showBody: false,
      config: {
        toolbar: [
          ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'Format', 'Styles', 'BulletedList', 'Link']
        ],
        height: 150
      },
    }
  }
}
</script>