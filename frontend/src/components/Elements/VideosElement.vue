<template>
  <div class="videos-element z-element z-component" :class="elementsClassObject" @click="onGlobalClick">
    <div class="preview">
      <div class="selection-overlay"></div>
      <div :class="optionClasses" @click="toggleOptionsPanel">
        <div class="placeholder-overlay"></div>
        <div v-if="isSingleVideo">
          <div class="video_ratio_container" :class="videoRatioClass">
            <iframe class="video-iframe" :src="firstVideoUrl" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
          </div>
        </div>
        <div v-if="!isSingleVideo" class="tns-outer" id="tns1-ow">
          <div class="tns-controls" aria-label="Carousel Navigation" tabindex="0">
              <button data-controls="prev" tabindex="-1" aria-controls="tns1" type="button"></button>
              <button data-controls="next" tabindex="-1" aria-controls="tns1" type="button"></button>
          </div>
          <div class="tns-ovh">
            <div class="tns-inner" id="tns1-iw">
              <div
                class="videos-slider tns-slider tns-carousel tns-subpixel tns-calc tns-horizontal"
                id="tns1"
                :class="numToShowClass"
                >
                <template v-for="(video, index) in Obj.contents.videos">
                <div :key="index" class="tns-item kd_col" aria-hidden="true" tabindex="-1">
                  <div class="video_ratio_container" :class="videoRatioClass">
                    <iframe class="video-iframe" :src="video" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                  </div>
                </div>
                </template>
              </div>
            </div>
          </div>
      </div>
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
        <br />
        <br />
        <span class="title-style">Videos</span>
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
          <label>
            Please copy/paste video link:
            <span class="icon material-icons help-btn" v-tooltip.bottom.end="'Multiple videos are separated as per line'">help</span>
          </label>
          <br />
          <br />
          <textarea :value="videosString" @blur="updateVideos($event)"></textarea>
          <hr>
          <label>Video Ratio:</label>
          <select :value="Obj.options.ratio" @blur="updateObj('ratio', 'options', $event.target.value)">
            <option>16:9</option>
            <option>4:3</option>
          </select>
          <hr>
          <label>How many videos to show in a row?</label>
          <select :value="Obj.options.numToShow" @blur="updateObj('numToShow', 'options', $event.target.value)">
            <option>1</option>
            <option>2</option>
            <option>3</option>
          </select>
        </div>
      </transition>
      <div class="option-console-container console-container">
        <a class="console-btn"><span>Update</span></a>
      </div>
    </div>
  </div>
</template>

<script>
import { default as _mixins } from '../../mixins';
export default {
  name: 'videos-element',
  label: 'Videos',
  
  mixins: [
    _mixins.element,
    _mixins.elementOptionsPanel,
  ],
  computed: {
    firstVideoUrl() {
      let videos = this.Obj.contents.videos;
      let firstUrl = null;
      if(videos.length > 0) {
        firstUrl = videos[0];
        if(firstUrl.match(/\?/gi)) {
          firstUrl += '&rel=0';
        }
        else {
          firstUrl += '?rel=0';
        }
      }
      return firstUrl;
    },
    isSingleVideo() {
      let videos = this.Obj.contents.videos;
      let result = true;
      if(videos.length > 1) {
        result = false;
      }
      return result;
    },
    numToShowClass() {
      const num = this.Obj.options.numToShow;
      return 'videos-show-' + num;
    },
    videosString() {
      let videos = this.Obj.contents.videos;
      return videos.join("\n");
    },
    videoRatioClass() {
      let ratio = this.Obj.options.ratio;
      let returnClass = null;
      switch(ratio) {
        case '4:3':
        returnClass = 'video_ratio_4_3';
      break;
      case '16:9':
        returnClass = 'video_ratio_16_9';
      }
      return returnClass;
    }
  },
  methods: {
    updateVideos(event) {
      this.Obj.contents.videos = event.target.value.split("\n");
    }
  },
  created() {
    if(Object.keys(this.Obj.contents).length == 0) {
      // Defaults
      this.Obj.contents.videos = [];
    }
    if(Object.keys(this.Obj.options).length == 0) {
      // Defaults
      this.Obj.options.numToShow = 1;
      this.Obj.options.ratio = '16:9';
    }
  }
}
</script>

<style lang="postcss" scoped>
@import '../../sass/Elements/VideosElement/scoped';
</style>