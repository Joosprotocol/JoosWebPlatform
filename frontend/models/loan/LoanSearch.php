<?php

namespace frontend\models\loan;

use common\models\loan\Loan;
use common\models\loan\LoanSearch as LoanSearchBase;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LoanSearch represents the model behind the search form about `custom_modules\loan\models\Loan`.
 */
class LoanSearch extends LoanSearchBase
{

    /** @var  integer */
    public $initTypeStrong;
    /** @var  integer */
    public $statusStrong;
    /** @var  integer */
    public $ownerId;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'lender_id', 'borrower_id', 'status', 'period', 'currency_type', 'init_type', 'created_at'], 'integer'],
            [['amount'], 'number'],
            [['hash_id'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Loan::find()
            ->alias('l')
            ->orderBy(['updated_at' => SORT_DESC]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!empty($params['pageSize'])) {
            $dataProvider->pagination->pageSize = $params['pageSize'];
        }

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'l.id' => $this->id,
            'l.lender_id' => $this->lender_id,
            'l.borrower_id' => $this->borrower_id,
            'l.status' => $this->status,
            'l.amount' => $this->amount,
            'l.period' => $this->period,
            'l.currency_type' => $this->currency_type,
            'l.init_type' => $this->init_type,
            'l.created_at' => $this->created_at,
            'l.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere([
            'l.init_type' => $this->initTypeStrong,
            'l.status' => $this->statusStrong
        ]);

        if ($this->ownerId !== null) {
            $query
                ->joinWith('loanReferrals as lr')
                ->andFilterWhere([
                'or',
                'l.lender_id=' . $this->ownerId,
                'l.borrower_id=' . $this->ownerId,
                'lr.digital_collector_id=' . $this->ownerId
            ]);
        }

        $query->andFilterWhere(['like', 'hash_id', $this->hash_id]);


        return $dataProvider;
    }
}
