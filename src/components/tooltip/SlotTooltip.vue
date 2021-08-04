<template>
  <div class="tooltip__container">
    <div class="button__wrapper">
      <div class="slot__wrapper">
        <slot name="button"></slot>
      </div>
    </div>
    <div class="tooltip__wrapper">
      <div class="slot__wrapper">
        <slot name="tooltip"></slot>
      </div>
    </div>
  </div>
</template>
