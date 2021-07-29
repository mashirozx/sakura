export default function (error: any) {
  if (error.response) {
    return {
      type: 'Response Error',
      msg: error.response.data.message ?? error.response.data, // Standard WP_Rest_Error
    }
  } else {
    return {
      type: 'Request Error',
      msg: error.message, // eg. network error
    }
  }
}
