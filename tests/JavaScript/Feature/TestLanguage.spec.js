import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Language from '../../../resources/js/components/Language';
import { languageFactory } from '../factory';

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
  });

  describe('editing', function () {
    beforeEach(function () { moxios.install(); });

    afterEach(function () { moxios.uninstall(); });

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
});
