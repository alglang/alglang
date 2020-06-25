const CALLBACK_NAME = 'gmapsCallback';

let initialized = !!window.google;
let resolveInitPromise;
let rejectInitPromise;

const initPromise = new Promise((resolve, reject) => {
  resolveInitPromise = resolve;
  rejectInitPromise = reject;
});

const init = apiKey => {
  if (initialized) {
    return initPromise;
  }

  initialized = true;
  window[CALLBACK_NAME] = () => resolveInitPromise(window.google);

  const script = document.createElement('script');
  script.async = true;
  script.defer = true;
  script.src = `https://maps.google.com/maps/api/js?key=${apiKey}&callback=${CALLBACK_NAME}`;
  script.onerror = rejectInitPromise;
  document.querySelector('head').appendChild(script);

  return initPromise
};

export default init;
