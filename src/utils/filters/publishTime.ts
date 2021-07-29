import intl from '@/locales'
import timeFormater from '@/utils/timeFormater'

export default function (publishTime: string, brief = false) {
  const publistTimeDate = new timeFormater(publishTime)
  if (brief) {
    return publistTimeDate.moreThanOneYear()
      ? intl.formatMessage(
          {
            id: 'posts.postTimeOn.brief',
            defaultMessage: '{publistTimeDate, date, long}',
          },
          { publistTimeDate: publistTimeDate.getDate() }
        )
      : intl.formatMessage(
          {
            id: 'posts.postTimeSince',
            defaultMessage: '{duration} ago',
          },
          { duration: publistTimeDate.getReadableTimeFromNowBrief() }
        )
  } else {
    return publistTimeDate.moreThanOneYear()
      ? intl.formatMessage(
          {
            id: 'posts.postTimeOn.full',
            defaultMessage: 'Post on {publistTimeDate, date, long}',
          },
          { publistTimeDate: publistTimeDate.getDate() }
        )
      : intl.formatMessage(
          {
            id: 'posts.postTimeSince',
            defaultMessage: 'Post {duration} ago',
          },
          { duration: publistTimeDate.getReadableTimeFromNow() }
        )
  }
}
