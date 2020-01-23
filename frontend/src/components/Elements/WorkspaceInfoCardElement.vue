<template>
  <div class="card-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <div :class="optionClasses" class="card"
        @click="toggleOptionsPanel"
      >
        <p v-if="Obj.contents.projectLogoUrl"><img width="100%" :src="Obj.contents.projectLogoUrl" alt="" /></p>
        <p v-if="Obj.contents.projectVisibility" class="icon icon-lock"><span>{{Obj.contents.projectVisibility}}</span></p>
        <p v-if="Obj.contents.projectMemberCount" class="icon icon-group"><span><b>{{Obj.contents.projectMemberCount}} members</b></span></p>
        <p v-if="Obj.contents.projectHomeUrl" class="icon icon-house"><a>Homepage</a></p>
        <p v-if="Obj.contents.projectContactEmail" class="icon icon-avatar"><a>{{Obj.contents.projectContactEmail}}</a></p>
      </div>
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
      </div>
      <div class="element-content console-container">
        <label>Logo url:</label>
        <br>
        <input
          placeholder="Workspace logo url"
          @blur="updateObj('projectLogoUrl', 'contents', $event.target.value)"
          @keyup.enter="updateObj('projectLogoUrl', 'contents', $event.target.value)"
          :value="Obj.contents.projectLogoUrl"
          class="inline-input" />
        <br />
        <br />
        <label>Visibility statement:</label>
        <br>
        <input
          placeholder="Workspace visibility statement"
          @blur="updateObj('projectVisibility', 'contents', $event.target.value)"
          @keyup.enter="updateObj('projectVisibility', 'contents', $event.target.value)"
          :value="Obj.contents.projectVisibility"
          class="inline-input" />
        <br />
        <br />
        <label>Members total number:</label>
        <br>
        <input
          placeholder="Workspace members total number"
          @blur="updateObj('projectMemberCount', 'contents', $event.target.value)"
          @keyup.enter="updateObj('projectMemberCount', 'contents', $event.target.value)"
          :value="Obj.contents.projectMemberCount"
          class="inline-input" />
        <br />
        <br />
        <label>Workspace url:</label>
        <br>
        <input
          placeholder="Workspace url"
          @blur="updateObj('projectHomeUrl', 'contents', $event.target.value)"
          @keyup.enter="updateObj('projectHomeUrl', 'contents', $event.target.value)"
          :value="Obj.contents.projectHomeUrl"
          class="inline-input" />
        <br />
        <br />
        <label>Contact email:</label>
        <br>
        <input
          placeholder="Workspace contact email"
          @blur="updateObj('projectContactEmail', 'contents', $event.target.value)"
          @keyup.enter="updateObj('projectContactEmail', 'contents', $event.target.value)"
          :value="Obj.contents.projectContactEmail"
          class="inline-input" />
      </div>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
export default {
  name: 'text-element',
  label: 'Workspace Info Card',

  mixins: [
    _mixins.element,
    _mixins.elementOptionsPanel,
    _mixins.elementOptions._bgColor,
    _mixins.elementOptions.iconblock_txtPosition,
    _mixins.elementOptions._icon
  ],
  data() {
    return {
      config: {
        toolbar: [
          ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', 'Format', 'Styles', 'BulletedList', 'Link']
        ],
        height: 300
      },
    }
  },
  created() {
    if(Object.keys(this.Obj.contents).length == 0) {
      // Defaults
      this.Obj.contents.projectLogoUrl = this.buildingScriptsInfo.projectLogoUrl;
      this.Obj.contents.projectVisibility = this.buildingScriptsInfo.projectVisibility;
      this.Obj.contents.projectMemberCount = this.buildingScriptsInfo.projectMemberCount;
      this.Obj.contents.projectHomeUrl = this.buildingScriptsInfo.projectHomeUrl;
      this.Obj.contents.projectContactEmail = this.buildingScriptsInfo.projectContactEmail;
    }
  }
}
</script>