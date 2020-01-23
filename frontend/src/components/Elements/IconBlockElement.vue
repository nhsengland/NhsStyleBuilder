<template>
  <div class="icon-block-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <a class="icon_block icon" :class="optionClasses"
        @click="toggleOptionsPanel">
        <h3>{{Obj.contents.BlockTitle}}</h3>
        <span v-html="Obj.contents.BlockText"></span>
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
        <span>{{$options.label}}: </span>
        <input
          placeholder="Type the title"
          @blur="updateObj('BlockTitle', 'contents', $event.target.value)"
          @keyup.enter="updateObj('BlockTitle', 'contents', $event.target.value)"
          :value="Obj.contents.BlockTitle"
          class="title-input" />
      </div>
      <div class="element-content console-container">
        <input
          placeholder="Type the url if any"
          @blur="updateObj('BlockUrl', 'contents', $event.target.value)"
          @keyup.enter="updateObj('BlockUrl', 'contents', $event.target.value)"
          :value="Obj.contents.BlockUrl"
          class="inline-input" />
          <br />
          <br />
          <vue-ckeditor
            v-if="showCkeditor"
            placeholder="Type the block text"
            @blur="updateObj('BlockText', 'contents', $event._.data)"
            @keyup.enter="updateObj('BlockText', 'contents', $event._.data)"
            :config="wysiwygConfig"
            :value="Obj.contents.BlockText" />
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
          <template v-for="(option, index) in Obj.options">
            <div class="" :key="index">
              <label>
                {{$this[index + 'Label']}}
              </label>
              <v-select
                class="element-option-selection"
                :class="'element-option-' + index"
                :options="$this[index + 'AllOptions']"
                label="label"
                :value="vSelectedOptions[index]"
                @input="updateObj(index, 'options', ($event.value) ? $event.value : $event)"
              >
                <template v-if="option.optionHtml" slot="option" slot-scope="option">
                  <span v-html="option.optionHtml"></span>
                </template>
              </v-select>
              <br />
            </div>
          </template>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
export default {
  name: 'icon-block-element',
  label: 'Icon Block',

  mixins: [
    _mixins.element,
    _mixins.elementOptionsPanel,
    _mixins.elementOptions._bgColor,
    _mixins.elementOptions.iconblock_txtPosition,
    _mixins.elementOptions._icon,
    _mixins.elementWysiwyg
  ],
  computed: {
    vSelectedOptions() {
      const _selectedBgColorKey = (this.Obj.options._bgColor) ? this.Obj.options._bgColor.replace(/^bg\-/i, '') : null;
      const _selectedIconblock_txtPositionKey = (this.Obj.options.iconblock_txtPosition) ? this.Obj.options.iconblock_txtPosition.replace(/^icon_block\-\-txt\-align\-/i, '') : null;
      const _selectedIconKey = (this.Obj.options._icon) ? this.Obj.options._icon.replace(/^icon\s_icon\-/i, '') : null;
      return {
        _bgColor: this._bgColorAllOptionsObject[_selectedBgColorKey],
        iconblock_txtPosition: this.iconblock_txtPositionAllOptionsObject[_selectedIconblock_txtPositionKey],
        _icon: this._iconAllOptionsObject[_selectedIconKey],
      }
    }
  },
  created() {
    if(Object.keys(this.Obj.options).length == 0) {
      // Defaults
      this.Obj.options._bgColor = 'bg-transparent';
      this.Obj.options.iconblock_txtPosition = 'icon_block--txt-align-center';
      this.Obj.options._icon = null;
    }
    if(Object.keys(this.Obj.contents).length == 0) {
      // Defaults
      this.Obj.contents.BlockTitle = 'Block Title';
      this.Obj.contents.BlockText = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>';
      this.Obj.contents.BlockUrl = null;
    }
  }
}
</script>