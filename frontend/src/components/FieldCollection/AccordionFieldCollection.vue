<template>
  <div class="accordion-field-collection z-field-collection">
    <input
      placeholder="Type the accordion title"
      @blur="updateObj('AccordionTitle', 'contents', $event.target.value)"
      @keyup.enter="updateObj('AccordionTitle', 'contents', $event.target.value)"
      :value="Obj.contents.AccordionTitle"
      class="title-input" />
    <br>
    <br>
    <div v-if="!showBody">
      {{Obj.contents.AccordionBody.replace(/(<([^>]+)>)/ig,"").replace('&nbsp;', ' ').substring(0, 28)}}...
    </div>
    <transition name="slide-fade">
        <div v-if="showBody">
          <vue-ckeditor
            placeholder="Type the accordion text"
            @blur="updateObj('AccordionBody', 'contents', $event._.data)"
            :config="config"
            :value="Obj.contents.AccordionBody" />
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
  name: 'accordion-field-collection',

  mixins: [
    _mixins.element,
  ],
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