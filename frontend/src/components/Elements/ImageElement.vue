<template>
  <div class="image-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <a :class="optionClasses" @click="toggleOptionsPanel">
        <img :src="Obj.contents.ImageSrc" :title="Obj.contents.ImageTitle" :alt="Obj.contents.ImageAlt" />
      </a>
    </div>
    <div class="inline-element-control">

    </div>
    <div class="options" v-if="Obj.elementOptionsPanel.active">
      <div class="element-control">
        <a class="icon icon_inline material-icons float-left"
          @click="closeOptionsPanel">arrow_back</a>
        <a class="icon icon_inline material-icons float-right"
          @click="removeThis">delete_outline</a>
      </div>
      <div class="element-content console-container">
        {{$options.label}}:
        <br />
        <br />
        <span class="title-style">{{imgName}}</span>
      </div>
      <div class="console-container">
        <input
            placeholder="Type the image url"
            @blur="updateObj('ImageSrc', 'contents', $event.target.value)"
            @keyup.enter="updateObj('ImageSrc', 'contents', $event.target.value)"
            :value="Obj.contents.ImageSrc"
            class="inline-input" />
      </div>
      <div class="console-container">
        <a
          class="console-configbody-toggle bg-transparent icon_block--icon-position-right icon icon_block icon_block--txt-align-left"
          :class="showConfigBody ? 'icon-arrow-up' : 'icon icon-arrow-down'"
          @click = "showConfigBody = !showConfigBody"
          >
          <span>
          {{$options.label}} Options:
          </span>
        </a>
      </div>
      <transition name="slide-fade">
        <div class="console-configbody" v-if="showConfigBody">
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
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
export default {
  name: 'image-element',
  label: 'Image',

  mixins: [
    _mixins.element,
    _mixins.elementOptionsPanel,
  ],
  computed: {
    imgName() {
      const src = this.Obj.contents.ImageSrc;
      let tmp = src.replace(/\?.*/i, '');
      let filename = tmp.substring(tmp.lastIndexOf('/')+1);
      return filename;
    }
  },
  created() {
    if(Object.keys(this.Obj.contents).length == 0) {
      // Defaults
      this.Obj.contents.ImageSrc = 'https://source.unsplash.com/800x600/?nhs+doctor+nurse/' + Math.floor((Math.random() * 100000) + 1);
      this.Obj.contents.ImageUrl = null;
      this.Obj.contents.ImageTitle = null;
      this.Obj.contents.ImageAlt = null;
    }
  }
}
</script>

<style lang="postcss" scoped>
@import '../../sass/Elements/ImageElement/scoped';
</style>