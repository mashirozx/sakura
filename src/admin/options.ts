export interface Options {
  [tag: string]: {
    title: string
    desc?: string
    icon: string
    options: Array<{
      namespace: string
      title: string
      desc?: string
      type: string
      default: any
      binds?: { [key: string]: any }
    }>
  }
}

const options: Options = {
  basic: {
    title: 'Basic',
    desc: 'The basic options',
    icon: 'fas fa-address-card',
    options: [
      {
        namespace: 'basic.siteTitle',
        title: 'Site title',
        desc: 'The site title',
        type: 'string',
        default: 'Opps',
      },
      {
        namespace: 'basic.switcher',
        title: 'Switcher',
        type: 'switcher',
        default: true,
        binds: {
          positiveLabel: 'current on',
          negativeLabel: 'current off',
          disabled: false,
        },
      },
      {
        namespace: 'basic.chooseTest',
        title: 'Choose Test',
        desc: 'wooooo',
        type: 'choose',
        default: NaN,
        binds: {
          options: [
            { label: 'op 1', disabled: false },
            { label: 'op 2', disabled: false },
            { label: 'op 3', disabled: false },
            { label: 'op 4', disabled: true },
          ],
          max: 2,
        },
      },
      {
        namespace: 'basic.optionsTest',
        title: 'Option Test',
        desc: 'wooooo',
        type: 'selection',
        default: [true, false, true],
        binds: {
          options: [
            { label: 'op 1', disabled: false },
            { label: 'op 2', disabled: false },
            { label: 'op 3', disabled: false },
            { label: 'op 4', disabled: true },
          ],
          max: 2,
        },
      },
      {
        namespace: 'basic.longString',
        title: 'Long string',
        desc: 'A long string',
        type: 'longString',
        default: 'Opps',
      },
      {
        namespace: 'basic.mediaPicker',
        title: 'Image picker',
        desc: 'Media picker',
        type: 'mediaPicker',
        default: [
          {
            id: 0,
            url: 'https://view.moezx.cc/images/2021/07/02/d5ab73174d18652d890e2f4d1b9bef8f.gif',
          },
          {
            id: 0,
            url: 'https://view.moezx.cc/images/2021/07/02/a90553bf5b67770e87a89b2ce204eaa7.gif',
          },
        ],
        binds: {
          title: 'Select Media',
          button: 'Use this media',
          type: 'image',
          multiple: true,
        },
      },
    ],
  },
  social: {
    title: 'Social',
    icon: 'fas fa-users',
    options: [
      {
        namespace: 'social.github',
        title: 'Github username',
        desc: 'Your <a href="https://github.com" target="_blank">Github</a> username',
        type: 'string',
        default: 'mashirozx',
      },
      { namespace: 'social.weibo', title: 'Weibo username', type: 'string', default: 'mashirozx' },
    ],
  },
  other: {
    title: 'Other',
    icon: 'fas fa-umbrella',
    options: [
      {
        namespace: 'other.hello',
        title: 'Hello world',
        type: 'string',
        default: 'world',
      },
    ],
  },
}

export default options
