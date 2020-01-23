<template>
  <div class="button-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <a class="kd_button" :class="optionClasses"
        @click="toggleOptionsPanel"
      ><span>{{Obj.contents.ButtonText}}</span></a>
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
        <input
          placeholder="Type the button text"
          @blur="updateObj('ButtonText', 'contents', $event.target.value)"
          @keyup.enter="updateObj('ButtonText', 'contents', $event.target.value)"
          :value="Obj.contents.ButtonText"
          class="title-input" />
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
            placeholder="Type the url if any"
            @blur="updateObj('ButtonUrl', 'contents', $event.target.value)"
            @keyup.enter="updateObj('ButtonUrl', 'contents', $event.target.value)"
            :value="Obj.contents.ButtonUrl"
            class="inline-input" />
          <br />
          <br />
          <template v-for="(option, index) in Obj.options">
            <div :key="index">
              <label>
                {{$this[index + 'Label']}}
              </label>
              <v-select
                class="element-option-selection"
                :class="'element-option-' + index"
                :options="$this[index + 'AllOptions']"
                :value="vSelectedOptions[index]"
                @input="updateObj(index, 'options', ($event.value) ? $event.value : $event)"
              >
                <template v-if="option.optionHtml" slot="option" slot-scope="option">
                  <span v-html="option.optionHtml"></span>
                </template>
              </v-select>
              <br>
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
  name: 'button-element',
  label: 'Button',

  mixins: [
    _mixins.element,
    _mixins.elementOptionsPanel,
    _mixins.elementOptions._bgColor,
    _mixins.elementOptions.btn_txtPosition,
    _mixins.elementOptions._icon,
    _mixins.elementOptions._iconButtonSize
  ],
  computed: {
    vSelectedOptions() {
      const _selectedBgColorKey = (this.Obj.options._bgColor) ? this.Obj.options._bgColor.replace(/^bg\-/i, '') : null;
      const _selectedBtn_txtPositionKey = (this.Obj.options.btn_txtPosition) ? this.Obj.options.btn_txtPosition.replace(/^kd_button\-\-txt\-align\-/i, '') : null;
      const _selectedIconKey = (this.Obj.options._icon) ? this.Obj.options._icon.replace(/^icon\s_icon\-/i, '') : null;
      const _selectedIconButtonSizeKey = (this.Obj.options._iconButtonSize) ? this.Obj.options._iconButtonSize.replace(/^icon_button_size\-/i, '') : null;
      return {
        _bgColor: this._bgColorAllOptionsObject[_selectedBgColorKey],
        btn_txtPosition: this.btn_txtPositionAllOptionsObject[_selectedBtn_txtPositionKey],
        _icon: this._iconAllOptionsObject[_selectedIconKey],
        _iconButtonSize: this._iconButtonSizeAllOptionsObject[_selectedIconButtonSizeKey]
      }
    }
  },
  created() {
    if(Object.keys(this.Obj.options).length == 0) {
      // Defaults
      this.Obj.options._bgColor = 'bg-default';
      this.Obj.options.btn_txtPosition = 'kd_button--txt-align-left';
      this.Obj.options._icon = null;
      this.Obj.options._iconButtonSize = 'icon_button_size-normal';
    }
    if(Object.keys(this.Obj.contents).length == 0) {
      // Defaults
      this.Obj.contents.ButtonText = 'Button Text';
      this.Obj.contents.ButtonUrl = null;
    }
  }
}
</script>