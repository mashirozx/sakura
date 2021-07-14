export interface Options {
  [tag: string]: {
    title: string
    icon: string
    options: Array<{
      namespace: string
      type: string
      default: any
    }>
  }
}

const options: Options = {
  basic: {
    title: 'Basic',
    icon: 'fas fa-address-card',
    options: [
      {
        namespace: 'basic.siteTitle',
        type: 'string',
        default: 'Opps',
      },
      {
        namespace: 'basic.userName',
        type: 'string',
        default: 'Mashiro',
      },
    ],
  },
  social: {
    title: 'Social',
    icon: 'fas fa-users',
    options: [
      { namespace: 'social.github', type: 'string', default: 'mashirozx' },
      { namespace: 'social.weibo', type: 'string', default: 'mashirozx' },
    ],
  },
  other: {
    title: 'Other',
    icon: 'fas fa-umbrella',
    options: [
      {
        namespace: 'other.hello',
        type: 'string',
        default: 'world',
      },
    ],
  },
}

export default options
