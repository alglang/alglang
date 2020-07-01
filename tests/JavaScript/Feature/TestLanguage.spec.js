import { render, waitForElementToBeRemoved } from '@testing-library/vue';
import { expect } from 'chai';
import moxios from 'moxios';

import Language from '../../../resources/js/components/Language.vue';
import BasicDetails from '../../../resources/js/components/Language/BasicDetails';
import Morphemes from '../../../resources/js/components/Language/Morphemes';
import { groupFactory, languageFactory } from '../factory';

describe('Language.vue', () => {
  it('displays its name in the header', () => {
    const props = {
      language: languageFactory({ name: 'Test Language' })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('Language details'));
    expect(getByText('Test Language'));
  });

  it('displays its detail page on initial render', () => {
    const props = {
      language: languageFactory({ algo_code: 'TL' })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('TL'));  // The algonquianist code should only appear on the detail page
  });

  it('indicates that the language is reconstructed', () => {
    const props = {
      language: languageFactory({ reconstructed: true })
    };

    const { getByText } = render(Language, { props });

    expect(getByText('Reconstructed'));
  });
});

describe('Language/BasicDetails.vue', () => {
  describe('displaying universally required data', () => {
    it('shows its algonquianist code', () => {
        const props = {
          value: languageFactory({ algo_code: 'TL' })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Algonquianist code'));
        expect(getByText('TL'));
    });

    it('shows its group', () => {
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

  describe('displaying location', () => {
    describe('when it has a position', () => {
      it('shows a map', () => {
        const props = {
          value: languageFactory({
            position: { lat: 90, lng: 45 }
          })
        };

        const { getByLabelText } = render(BasicDetails, { props });

        expect(getByLabelText('Location'));
      });
    });

    describe('has no position', () => {
      it('does not show a map', () => {
        const props = {
          value: languageFactory({ position: null })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Location')).to.be.null;
      });
    });
  });

  describe('displaying children', () => {
    describe('when it has children', () => {
      it('shows its children', () => {
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

    describe('when it has no children', () => {
      it('does not show the children section', () => {
        const props = {
          value: languageFactory({ children: [] })
        };

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Direct children')).to.be.null;
      });
    });
  });

  describe('displaying notes', () => {
    describe('when it has notes', () => {
      it('shows its notes', () => {
        const props = {
          value: languageFactory({ notes: 'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam' })
        };

        const { getByLabelText, getByText } = render(BasicDetails, { props });

        expect(getByLabelText('Notes'));
        expect(getByText('Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam'));
      });
    });

    describe('when it has no notes', () => {
      it('does not show its notes', () => {
        const props = {
          value: languageFactory({ notes: null })
        }

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Notes')).to.be.null;
      })
    });

    describe('when it has empty notes', () => {
      it('does not show its notes', () => {
        const props = {
          value: languageFactory({ notes: '' })
        }

        const { queryByLabelText } = render(BasicDetails, { props });

        expect(queryByLabelText('Notes')).to.be.null;
      })
    });
  })
});

describe('Language/Morphemes.vue', () => {
  beforeEach(() => moxios.install());

  afterEach(() => moxios.uninstall());

  it('displays loading text when it is first mounted', () => {
    const props = {
      value: languageFactory({ url: '/languages/tl' })
    };

    const { getByText } = render(Morphemes, { props });

    expect(getByText('Loading...'));
  });

  it('loads morphemes', async () => {
    const props = {
      value: languageFactory({ url: '/languages/tl' })
    };

    const { getByText } = render(Morphemes, { props });

    moxios.stubRequest('/languages/tl/morphemes', {
      status: 200,
      response: {
        data: [
          {
            shape: 'aa-',
            slot: {
              abv: 'foo'
            }
          }
        ]
      }
    })

    await waitForElementToBeRemoved(getByText('Loading...'));

    expect(getByText('aa-'));
    expect(getByText('foo'));
  });

  it('only loads the first 10 morphemes', async () => {
    const props = {
      value: languageFactory({ url: '/languages/tl' })
    };

    const { getByText } = render(Morphemes, { props });
  });
});
