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

describe('Language.vue', function () {
  describe('viewing', function () {
    it('displays its name in the header', function () {
      const props = {
        language: languageFactory({ name: 'Test Language' })
      };

      const { getByText } = render(Language, { props });

      expect(getByText('Language details'));
      expect(getByText('Test Language'));
    });

    it('displays its detail page on initial render', function () {
      const props = {
        language: languageFactory({ algo_code: 'TL' })
      };

      const { getByText } = render(Language, { props });

      expect(getByText('TL')); // The algonquianist code should only appear on the detail page
    });

    it('indicates that the language is reconstructed', function () {
      const props = {
        language: languageFactory({ reconstructed: true })
      };

      const { getByText } = render(Language, { props });

      expect(getByText('Reconstructed'));
    });

    it('shows an edit button when the user can edit', function () {
      const props = {
        mode: 'view',
        canEdit: true,
        language: languageFactory()
      };

      const { getByLabelText } = render(Language, { props });

      expect(getByLabelText('Edit')).to.exist;
    });

    it('does not show an edit button when the user cannot edit', function () {
      const props = {
        mode: 'view',
        canEdit: false,
        language: languageFactory()
      };

      const { queryByLabelText } = render(Language, { props });

      expect(queryByLabelText('Edit')).to.not.exist;
    });
  });

  describe('editing', function () {
    beforeEach(function () { moxios.install(); });

    afterEach(function () { moxios.uninstall(); });

    it('switches to edit mode when the edit button is pressed', async function () {
      const props = {
        mode: 'view',
        canEdit: true,
        language: languageFactory()
      };

      const { getByLabelText, queryByLabelText } = render(Language, { props });

      expect(queryByLabelText('Name')).to.not.exist;

      await fireEvent.click(getByLabelText('Edit'));

      expect(queryByLabelText('Name')).to.exist;
    });

    it('shows a save button when in edit mode', function () {
      const props = {
        mode: 'edit',
        canEdit: true
      };

      const { queryByLabelText } = render(Language, { props });

      expect(queryByLabelText('Save')).to.exist;
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

      const props = { mode: 'edit' };

      const { queryByText, getByText } = render(Language, { props });

      await waitFor(() => expect(getByText('Loading...')));
      await waitForElementToBeRemoved(queryByText('Loading...'));

      expect(getByText('Factory Language'));
      expect(getByText('Factory Group'));
    });

    it('displays an input for its name', function () {
      const props = { mode: 'edit' };

      const { getByLabelText } = render(Language, { props });

      expect(getByLabelText('Name')).to.have.tagName('input');
    });

    it('binds the name input to its name', function () {
      const props = {
        mode: 'edit',
        language: { name: 'Test Language' }
      };

      const { getByLabelText } = render(Language, { props });

      expect(getByLabelText('Name')).to.have.value('Test Language');
    });

    it('displays a checkbox for its reconstructed value', function () {
      const props = { mode: 'edit' };

      const { getByLabelText } = render(Language, { props });

      expect(getByLabelText('Reconstructed')).to.have.tagName('input');
    });

    it('binds the reconstructed checkbox to its reconstructed value', function () {
      const props = {
        mode: 'edit',
        language: { reconstructed: true }
      };

      const { getByLabelText } = render(Language, { props });

      expect(getByLabelText('Reconstructed').checked).to.be.true;
    });
  });

  describe('saving', function () {
    beforeEach(function () { moxios.install(); });

    afterEach(function () { moxios.uninstall(); });

    it('switches to view mode', async function () {
      const props = {
        mode: 'edit',
        canEdit: true,
        language: languageFactory()
      };

      const { getByLabelText, queryByLabelText } = render(Language, { props });

      expect(queryByLabelText('Name')).to.exist;

      await fireEvent.click(getByLabelText('Save'));

      expect(queryByLabelText('Name')).to.not.exist;
    });

    it('sends its data to the server', async function () {
      moxios.stubRequest('POST', '/languages', {
        status: 200
      });

      const props = {
        mode: 'edit',
        canEdit: true,
        language: languageFactory({
          name: 'Test Language',
          algo_code: 'TL',
          group_id: 440,
          group: groupFactory({
            name: 'Test Group'
          })
        })
      };

      const { getByLabelText } = render(Language, { props });

      await fireEvent.click(getByLabelText('Save'));

      await waitFor(() => expect(moxios.requests.count()).to.equal(3)); // +2 for the resource loads

      const request = moxios.requests.mostRecent();
      expect(request.config.url).to.equal('/languages');
      expect(JSON.parse(request.config.data)).to.deep.equal({
        name: 'Test Language',
        algo_code: 'TL',
        group_id: 440,
        group: {
          name: 'Test Group'
        }
      });
    });
  });
});
