import '../setup';
import { render } from '@testing-library/vue';
import { expect } from 'chai';

import BasicDetails from '../../../resources/js/components/Language/BasicDetails';
import { groupFactory, languageFactory } from '../factory';

describe('Language/BasicDetails.vue', function () {
  describe('displaying universally required data', function () {
    describe('viewing', function () {
      it('shows its algonquianist code', function () {
        const props = {
          value: languageFactory({ algo_code: 'TL' })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Algonquianist code'));
        expect(getByText('TL'));
      });

      it('shows its group', function () {
        const props = {
          value: languageFactory({
            group: groupFactory({ name: 'Test Group' })
          })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Group'));
        expect(getByText('Test Group'));
      });
    });

    describe('displaying location', function () {
      describe('when it has a position', function () {
        it('shows a map', function () {
          const props = {
            value: languageFactory({
              position: { lat: 90, lng: 45 }
            })
          };

          const { getByLabelText } = render(BasicDetails, { props });

          expect(getByLabelText('Location'));
        });
      });

      describe('has no position', function () {
        it('does not show a map', function () {
          const props = {
            value: languageFactory({ position: null })
          };

          const { queryByLabelText } = render(BasicDetails, { props });

          expect(queryByLabelText('Location')).to.be.null;
        });
      });
    });

    describe('displaying its parent', function () {
      describe('when it has a parent', function () {
        it('shows its parent', function () {
          const props = {
            value: languageFactory({
              parent: languageFactory({ name: 'Parent language' })
            })
          };

          const { getByLabelText } = render(BasicDetails, { props });

          expect(getByLabelText('Parent')).to.contain.trimmed.text('Parent language');
        });

        it('links its parent', function () {
          const props = {
            value: languageFactory({
              parent: languageFactory({ url: '/languages/PL' })
            })
          };

          const { getByLabelText } = render(BasicDetails, { props });

          expect(getByLabelText('Parent').querySelector('a')).to.have.attribute('href', '/languages/PL');
        });
      });
    });

    describe('displaying children', function () {
      describe('when it has children', function () {
        it('shows its children', function () {
          const props = {
            value: languageFactory({
              children: [
                languageFactory({ name: 'Child Language 1' }),
                languageFactory({ name: 'Child Language 2' })
              ]
            })
          };

          const { getByLabelText, getByText } = render(BasicDetails, { props });

          expect(getByLabelText('Direct children'));
          expect(getByText('Child Language 1'));
          expect(getByText('Child Language 2'));
        });
      });

      describe('when it has no children', function () {
        it('does not show the children section', function () {
          const props = {
            value: languageFactory({ children: [] })
          };

          const { queryByLabelText } = render(BasicDetails, { props });

          expect(queryByLabelText('Direct children')).to.be.null;
        });
      });
    });

    describe('displaying notes', function () {
      describe('when it has notes', function () {
        it('shows its notes', function () {
          const props = {
            value: languageFactory({ notes: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' })
          };

          const { getByLabelText, getByText } = render(BasicDetails, { props });

          expect(getByLabelText('Notes'));
          expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
        });
      });

      describe('when it has no notes', function () {
        it('does not show its notes', function () {
          const props = {
            value: languageFactory({ notes: null })
          };

          const { queryByLabelText } = render(BasicDetails, { props });

          expect(queryByLabelText('Notes')).to.be.null;
        });
      });

      describe('when it has empty notes', function () {
        it('does not show its notes', function () {
          const props = {
            value: languageFactory({ notes: '' })
          };

          const { queryByLabelText } = render(BasicDetails, { props });

          expect(queryByLabelText('Notes')).to.be.null;
        });
      });
    });
  });

  describe('editing', function () {
    it('shows a text input for its algonquianist code', function () {
      const props = {
        mode: 'edit',
        value: { algo_code: 'TL' }
      };

      const { getByLabelText } = render(BasicDetails, { props });

      expect(getByLabelText('Algonquianist code')).to.have.descendant('input');
      expect(getByLabelText('Algonquianist code').querySelector('input')).to.have.value('TL');
    });

    it('shows a select for its group', function () {
      const props = {
        mode: 'edit',
        value: { group_id: 440 },
        resources: {
          groups: [groupFactory({ id: 440 })]
        }
      };

      const { getByLabelText } = render(BasicDetails, { props });

      expect(getByLabelText('Group')).to.have.descendant('select');
      expect(getByLabelText('Group').querySelector('select')).to.have.value('440');
    });

    it('shows a select for its parent', function () {
      const props = {
        mode: 'edit',
        value: { parent_id: 440 },
        resources: {
          languages: [languageFactory({ id: 440 })]
        }
      };

      const { getByLabelText } = render(BasicDetails, { props });

      expect(getByLabelText('Parent')).to.have.descendant('select');
      expect(getByLabelText('Parent').querySelector('select')).to.have.value('440');
    });

    it('disables its children', function () {
      const props = {
        mode: 'edit',
        value: { children: [languageFactory()] }
      };

      const { getByLabelText } = render(BasicDetails, { props });

      expect(getByLabelText('Direct children')).to.have.class('disabled');
    });

    it('shows a textarea for its notes', function () {
      const props = {
        mode: 'edit',
        value: { notes: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' }
      };

      const { getByLabelText } = render(BasicDetails, { props });

      expect(getByLabelText('Notes')).to.have.descendant('textarea');
      expect(getByLabelText('Notes').querySelector('textarea')).to.have.value('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam');
    });

    it('shows a textarea for its notes even if it has no notes', function () {
      const props = {
        mode: 'edit',
        value: { notes: null }
      };

      const { getByLabelText } = render(BasicDetails, { props });

      expect(getByLabelText('Notes')).to.have.descendant('textarea');
      expect(getByLabelText('Notes').querySelector('textarea')).to.have.value('');
    });

    it('shows a map even if it has no position', function () {
      const props = {
        mode: 'edit',
        value: { position: null }
      };

      const { getByLabelText } = render(BasicDetails, { props });

      expect(getByLabelText('Location'));
    });
  });
});
