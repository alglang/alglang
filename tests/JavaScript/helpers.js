import { waitFor, waitForElementToBeRemoved } from '@testing-library/vue';
import moxios from 'moxios';
import { expect } from 'chai';

export const mockResources = (...uris) => {
  uris.forEach(uri => moxios.stubRequest(uri, {
    response: { data: [] }
  }));
};

export const waitForTransient = async query => {
  await waitFor(() => expect(query()));
  await waitForElementToBeRemoved(query());
};
