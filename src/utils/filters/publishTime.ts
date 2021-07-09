import intl from '@/locales'
import timeFormater from '@/utils/timeFormater'

export default function (publishTime: string) {
  const publistTimeDate = new timeFormater(publishTime)
  const publistTime = publistTimeDate.moreThanOneYear()
    ? intl.formatMessage(
        {
          id: 'posts.postTimeOn',
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
  return publistTime
}
