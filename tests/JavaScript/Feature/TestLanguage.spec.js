import '../setup';
import {
  render,
  fireEvent,
  waitFor,
  waitForElementToBeRemoved
} from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Language from '../../../resources/js/components/Language';
import { groupFactory, languageFactory } from '../factory';
import { mockResources, waitForTransient } from '../helpers';

const renderLanguage = props => render(Language, {
  props: {
    language: languageFactory(),
    ...props
  }
});

const renderLanguageAndWaitForResources = async props => {
  mockResources('/languages', '/groups');
  const wrapper = renderLanguage(props);
  await waitForElementToBeRemoved(() => wrapper.getByText('Loading...'));
  return wrapper;
};

describe('Language.vue', function () {
  describe('viewing', function () {
    it('displays its name in the header', function () {
      const { queryByText } = renderLanguage({
        language: languageFactory({ name: 'Test Language' })
      });

      expect(queryByText('Test Language')).to.exist;
    });

    it('displays its detail page on initial render', function () {
      const { queryByText } = renderLanguage({
        language: languageFactory({ algo_code: 'TL' })
      });

      expect(queryByText('TL')).to.exist; // The algonquianist code should only appear on the detail page
    });

    describe('when the language is reconstructed', function () {
      it('indicates that the language is reconstructed', function () {
        const { queryByText } = renderLanguage({
          language: languageFactory({ reconstructed: true })
        });

        expect(queryByText('Reconstructed')).to.exist;
      });
    });

    describe('when the language is not reconstructed', function () {
      it('does not indicate that the language is reconstructed', function () {
        const { queryByText } = renderLanguage({
          language: languageFactory({ reconstructed: false })
        });

        expect(queryByText('Reconstructed')).to.not.exist;
      });
    });

    it('shows an edit button when the user can edit', function () {
      const { queryByLabelText } = renderLanguage({ canEdit: true, mode: 'view' });
      expect(queryByLabelText('Edit')).to.exist;
    });

    it('does not show an edit button when the user cannot edit', function () {
      const { queryByLabelText } = renderLanguage({ canEdit: false, mode: 'view' });
      expect(queryByLabelText('Edit')).to.not.exist;
    });
  });

  describe('editing', function () {
    beforeEach(function () { moxios.install(); });

    afterEach(function () { moxios.uninstall(); });

    it('switches to edit mode when the edit button is pressed', async function () {
      const { getByLabelText, queryByLabelText } = renderLanguage({ canEdit: true, mode: 'view' });
      expect(queryByLabelText('Name')).to.not.exist;

      await fireEvent.click(getByLabelText('Edit'));

      expect(queryByLabelText('Name')).to.exist;
    });

    it('shows a save button when in edit mode', function () {
      const { queryByLabelText } = renderLanguage({ canEdit: true, mode: 'edit' });
      expect(queryByLabelText('Save')).to.exist;
    });

    it('does not show an edit button when in save mode', function () {
      const { queryByLabelText } = renderLanguage({ canEdit: true, mode: 'edit' });
      expect(queryByLabelText('Edit')).to.not.exist;
    });

    it('loads resources from the server', async function () {
      moxios.stubRequest('/groups', {
        status: 200,
        response: {
          data: [groupFactory({ name: 'Factory Group' })]
        }
      });
      moxios.stubRequest('/languages', {
        status: 200,
        response: {
          data: [languageFactory({ name: 'Factory Language' })]
        }
      });

      const { queryByText } = renderLanguage({ mode: 'edit' });

      await waitForTransient(() => queryByText('Loading...'));

      expect(queryByText('Factory Language')).to.exist;
      expect(queryByText('Factory Group')).to.exist;
    });

    it('displays an input for its name', function () {
      const { getByLabelText } = renderLanguage({ mode: 'edit' });
      expect(getByLabelText('Name')).to.have.tagName('input');
    });

    it('binds the name input to its name', function () {
      const { getByLabelText } = renderLanguage({
        mode: 'edit',
        language: languageFactory({ name: 'Test Language' })
      });

      expect(getByLabelText('Name')).to.have.value('Test Language');
    });

    it('displays a checkbox for its reconstructed value', function () {
      const { getByLabelText } = renderLanguage({ mode: 'edit' });
      expect(getByLabelText('Reconstructed')).to.have.tagName('input');
      expect(getByLabelText('Reconstructed')).to.have.attribute('type', 'checkbox');
    });

    it('binds the reconstructed checkbox to its reconstructed value', function () {
      const { getByLabelText } = renderLanguage({
        mode: 'edit',
        language: { reconstructed: true }
      });

      expect(getByLabelText('Reconstructed').checked).to.be.true;
    });

    it('can be constructed without an explicit language', function () {
      const { getByLabelText } = renderLanguage({ language: undefined, mode: 'edit' });
      expect(getByLabelText('Name')).to.have.value('');
      expect(getByLabelText('Reconstructed').checked).to.be.false;
    });
  });

  describe('saving', function () {
    beforeEach(function () { moxios.install(); });

    afterEach(function () { moxios.uninstall(); });

    it('switches to view mode', async function () {
      const { getByLabelText, queryByLabelText } = renderLanguage({ canEdit: true, mode: 'edit' });

      expect(queryByLabelText('Name')).to.exist;

      await fireEvent.click(getByLabelText('Save'));

      expect(queryByLabelText('Name')).to.not.exist;
    });

    it('sends its data to the server', async function () {
      moxios.stubRequest('POST', '/languages');

      const { getByLabelText } = await renderLanguageAndWaitForResources({
        mode: 'edit',
        canEdit: true,
        language: {
          name: 'Test Language',
          group: { name: 'Test Group' }
        }
      });

      const requestsCount = moxios.requests.count();

      await fireEvent.click(getByLabelText('Save'));

      await waitFor(() => expect(moxios.requests.count()).to.equal(requestsCount + 1));

      const request = moxios.requests.mostRecent();
      expect(request.config.url).to.equal('/languages');
      expect(JSON.parse(request.config.data)).to.deep.equal({
        name: 'Test Language',
        group: { name: 'Test Group' }
      });
    });
  });
});
