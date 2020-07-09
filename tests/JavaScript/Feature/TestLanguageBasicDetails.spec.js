import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Language/BasicDetails';
import { groupFactory, languageFactory } from '../factory';

const renderBasicDetails = props => render(BasicDetails, { props });

describe('Language/BasicDetails.vue', function () {
  describe('displaying universally required data', function () {
    describe('viewing', function () {
      it('shows its algonquianist code', function () {
        const { getByLabelText } = renderBasicDetails({
          value: languageFactory({ algo_code: 'TL' })
        });

        expect(getByLabelText('Algonquianist code')).to.contain.trimmed.text('TL');
      });

      it('shows its group', function () {
        const { getByLabelText } = renderBasicDetails({
          value: languageFactory({
            group: groupFactory({ name: 'Test Group' })
          })
        });

        expect(getByLabelText('Group')).to.contain.trimmed.text('Test Group');
      });
    });

    describe('displaying location', function () {
      describe('when it has a position', function () {
        it('shows a map', function () {
          const { queryByLabelText } = renderBasicDetails({
            value: languageFactory({
              position: { lat: 90, lng: 45 }
            })
          });

          expect(queryByLabelText('Location')).to.exist;
        });
      });

      describe('has no position', function () {
        it('does not show a map', function () {
          const { queryByLabelText } = renderBasicDetails({
            value: languageFactory({ position: null })
          });

          expect(queryByLabelText('Location')).to.not.exist;
        });
      });
    });

    describe('displaying its parent', function () {
      describe('when it has a parent', function () {
        it('shows its parent', function () {
          const { getByLabelText } = renderBasicDetails({
            value: languageFactory({
              parent: languageFactory({ name: 'Parent language' })
            })
          });

          expect(getByLabelText('Parent')).to.contain.trimmed.text('Parent language');
        });

        it('links its parent', function () {
          const { getByLabelText } = renderBasicDetails({
            value: languageFactory({
              parent: languageFactory({ url: '/languages/PL' })
            })
          });

          expect(getByLabelText('Parent').querySelector('a')).to.have.attribute('href', '/languages/PL');
        });
      });
    });

    describe('displaying children', function () {
      describe('when it has children', function () {
        it('shows its children', function () {
          const { getByLabelText } = renderBasicDetails({
            value: languageFactory({
              children: [
                languageFactory({ name: 'Child Language 1' }),
                languageFactory({ name: 'Child Language 2' })
              ]
            })
          });

          expect(getByLabelText('Direct children')).to.contain.trimmed.text('Child Language 1');
          expect(getByLabelText('Direct children')).to.contain.trimmed.text('Child Language 2');
        });
      });

      describe('when it has no children', function () {
        it('does not show the children section', function () {
          const { queryByLabelText } = renderBasicDetails({
            value: languageFactory({ children: [] })
          });

          expect(queryByLabelText('Direct children')).to.not.exist;
        });
      });
    });

    describe('displaying notes', function () {
      describe('when it has notes', function () {
        it('shows its notes', function () {
          const { getByLabelText } = renderBasicDetails({
            value: languageFactory({ notes: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' })
          });

          expect(getByLabelText('Notes')).to.contain.trimmed.text('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
        });
      });

      describe('when it has no notes', function () {
        it('does not show its notes', function () {
          const { queryByLabelText } = renderBasicDetails({
            value: languageFactory({ notes: null })
          });

          expect(queryByLabelText('Notes')).to.not.exist;
        });
      });
    });
  });

  describe('editing', function () {
    it('shows a text input for its algonquianist code', function () {
      const { getByLabelText } = renderBasicDetails({
        mode: 'edit',
        value: { algo_code: 'TL' }
      });

      expect(getByLabelText('Algonquianist code')).to.have.descendant('input');
      expect(getByLabelText('Algonquianist code').querySelector('input')).to.have.value('TL');
    });

    it('shows a select for its group', function () {
      const { getByLabelText } = renderBasicDetails({
        mode: 'edit',
        value: { group_id: 440 },
        resources: {
          groups: [groupFactory({ id: 440 })]
        }
      });

      expect(getByLabelText('Group')).to.have.descendant('select');
      expect(getByLabelText('Group').querySelector('select')).to.have.value('440');
    });

    it('shows a select for its parent', function () {
      const { getByLabelText } = renderBasicDetails({
        mode: 'edit',
        value: { parent_id: 440 },
        resources: {
          languages: [languageFactory({ id: 440 })]
        }
      });

      expect(getByLabelText('Parent')).to.have.descendant('select');
      expect(getByLabelText('Parent').querySelector('select')).to.have.value('440');
    });

    it('disables its children', function () {
      const { getByLabelText } = renderBasicDetails({
        mode: 'edit',
        value: { children: [languageFactory()] }
      });

      expect(getByLabelText('Direct children')).to.have.class('disabled');
    });

    it('shows a textarea for its notes', function () {
      const { getByLabelText } = renderBasicDetails({
        mode: 'edit',
        value: { notes: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' }
      });

      expect(getByLabelText('Notes')).to.have.descendant('textarea');
      expect(getByLabelText('Notes').querySelector('textarea')).to.have.value('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    });

    it('shows a textarea for its notes even if it has no notes', function () {
      const { getByLabelText } = renderBasicDetails({
        mode: 'edit',
        value: { notes: null }
      });

      expect(getByLabelText('Notes')).to.have.descendant('textarea');
      expect(getByLabelText('Notes').querySelector('textarea')).to.have.value('');
    });

    it('shows a map even if it has no position', function () {
      const { getByLabelText } = renderBasicDetails({
        mode: 'edit',
        value: { position: null }
      });

      expect(getByLabelText('Location'));
    });
  });
});
